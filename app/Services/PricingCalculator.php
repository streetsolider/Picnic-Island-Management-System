<?php

namespace App\Services;

use App\Models\DayTypePricing;
use App\Models\DurationDiscount;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomTypePricing;
use App\Models\SeasonalPricing;
use App\Models\ViewPricing;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PricingCalculator
{
    /**
     * Calculate room price for a booking period
     *
     * @param Room $room
     * @param Carbon $checkIn
     * @param Carbon $checkOut
     * @return array
     */
    public function calculateRoomPrice(Room $room, Carbon $checkIn, Carbon $checkOut): array
    {
        $hotel = $room->hotel;
        $numberOfNights = $checkIn->diffInDays($checkOut);

        if ($numberOfNights < 1) {
            throw new \InvalidArgumentException('Check-out must be at least 1 day after check-in');
        }

        // Step 1: Get base price for room type
        $basePrice = $this->getBasePrice($hotel, $room->room_type);

        if ($basePrice === null) {
            throw new \Exception("No pricing configured for room type: {$room->room_type}");
        }

        // Step 2: Get view modifier
        $viewModifier = $this->getViewModifier($hotel, $room->view_id);
        $priceWithView = $basePrice;
        $viewAdjustment = 0;

        if ($viewModifier) {
            $priceWithView = $viewModifier->applyModifier($basePrice);
            $viewAdjustment = $priceWithView - $basePrice;
        }

        // Step 3 & 4: Calculate price for each night (applying seasonal and day-type modifiers)
        $nightlyBreakdown = [];
        $totalBeforeDiscount = 0;
        $totalSeasonalAdjustment = 0;
        $totalDayTypeAdjustment = 0;

        $period = CarbonPeriod::create($checkIn, $checkOut->copy()->subDay());

        foreach ($period as $date) {
            $nightPrice = $priceWithView;
            $seasonalAdj = 0;
            $dayTypeAdj = 0;

            // Apply seasonal pricing
            $seasonalPricing = SeasonalPricing::getForDate($hotel->id, $date);
            if ($seasonalPricing) {
                $priceAfterSeasonal = $seasonalPricing->applyModifier($nightPrice);
                $seasonalAdj = $priceAfterSeasonal - $nightPrice;
                $nightPrice = $priceAfterSeasonal;
            }

            // Apply day-type pricing
            $dayTypePricing = DayTypePricing::getForDate($hotel->id, $date);
            if ($dayTypePricing) {
                $priceAfterDayType = $dayTypePricing->applyModifier($nightPrice);
                $dayTypeAdj = $priceAfterDayType - $nightPrice;
                $nightPrice = $priceAfterDayType;
            }

            $nightlyBreakdown[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'base_with_view' => round($priceWithView, 2),
                'seasonal_adjustment' => round($seasonalAdj, 2),
                'day_type_adjustment' => round($dayTypeAdj, 2),
                'final_price' => round($nightPrice, 2),
                'seasonal_name' => $seasonalPricing?->season_name,
                'day_type_name' => $dayTypePricing?->day_type_name,
            ];

            $totalBeforeDiscount += $nightPrice;
            $totalSeasonalAdjustment += $seasonalAdj;
            $totalDayTypeAdjustment += $dayTypeAdj;
        }

        // Step 5: Apply duration discount
        $durationDiscount = DurationDiscount::getForDuration($hotel->id, $numberOfNights);
        $discountAmount = 0;
        $finalTotal = $totalBeforeDiscount;

        if ($durationDiscount) {
            $finalTotal = $durationDiscount->applyDiscount($totalBeforeDiscount);
            $discountAmount = $totalBeforeDiscount - $finalTotal;
        }

        // Calculate averages
        $averagePricePerNight = $finalTotal / $numberOfNights;

        return [
            'currency' => 'MVR',
            'number_of_nights' => $numberOfNights,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),

            // Base pricing
            'room_type' => $room->room_type,
            'base_price' => round($basePrice, 2),
            'view_name' => $room->view?->name,
            'view_adjustment' => round($viewAdjustment, 2),
            'price_with_view' => round($priceWithView, 2),

            // Adjustments
            'total_seasonal_adjustment' => round($totalSeasonalAdjustment, 2),
            'total_day_type_adjustment' => round($totalDayTypeAdjustment, 2),

            // Discount
            'duration_discount_name' => $durationDiscount?->discount_name,
            'discount_amount' => round($discountAmount, 2),

            // Totals
            'subtotal_before_discount' => round($totalBeforeDiscount, 2),
            'total_price' => round($finalTotal, 2),
            'average_price_per_night' => round($averagePricePerNight, 2),

            // Detailed breakdown
            'nightly_breakdown' => $nightlyBreakdown,

            // Summary for display
            'summary' => $this->generateSummary(
                $basePrice,
                $viewAdjustment,
                $totalSeasonalAdjustment,
                $totalDayTypeAdjustment,
                $discountAmount,
                $numberOfNights
            ),
        ];
    }

    /**
     * Get base price for room type
     */
    public function getBasePrice(Hotel $hotel, string $roomType): ?float
    {
        return RoomTypePricing::getPriceForRoomType($hotel->id, $roomType);
    }

    /**
     * Get view pricing modifier
     */
    public function getViewModifier(Hotel $hotel, ?int $viewId): ?ViewPricing
    {
        if (!$viewId) {
            return null;
        }

        return ViewPricing::getModifierForView($hotel->id, $viewId);
    }

    /**
     * Generate a human-readable summary
     */
    private function generateSummary(
        float $basePrice,
        float $viewAdjustment,
        float $seasonalAdjustment,
        float $dayTypeAdjustment,
        float $discountAmount,
        int $nights
    ): array {
        $summary = [];

        $summary[] = "Base price: MVR " . number_format($basePrice, 2);

        if ($viewAdjustment > 0) {
            $summary[] = "View premium: +MVR " . number_format($viewAdjustment, 2);
        }

        if ($seasonalAdjustment != 0) {
            $prefix = $seasonalAdjustment > 0 ? '+' : '';
            $summary[] = "Seasonal adjustment: {$prefix}MVR " . number_format($seasonalAdjustment, 2);
        }

        if ($dayTypeAdjustment != 0) {
            $prefix = $dayTypeAdjustment > 0 ? '+' : '';
            $summary[] = "Day type adjustment: {$prefix}MVR " . number_format($dayTypeAdjustment, 2);
        }

        $summary[] = "Total for {$nights} night(s)";

        if ($discountAmount > 0) {
            $summary[] = "Duration discount: -MVR " . number_format($discountAmount, 2);
        }

        return $summary;
    }

    /**
     * Calculate price for multiple rooms
     */
    public function calculateMultipleRooms(array $rooms, Carbon $checkIn, Carbon $checkOut): array
    {
        $totalPrice = 0;
        $roomBreakdowns = [];

        foreach ($rooms as $room) {
            $pricing = $this->calculateRoomPrice($room, $checkIn, $checkOut);
            $roomBreakdowns[] = [
                'room_number' => $room->room_number,
                'room_type' => $room->room_type,
                'pricing' => $pricing,
            ];
            $totalPrice += $pricing['total_price'];
        }

        return [
            'total_price' => round($totalPrice, 2),
            'currency' => 'MVR',
            'number_of_rooms' => count($rooms),
            'rooms' => $roomBreakdowns,
        ];
    }

    /**
     * Get quick price estimate (simplified, faster)
     */
    public function getQuickEstimate(string $roomType, int $hotelId, int $nights = 1): ?array
    {
        $basePrice = RoomTypePricing::getPriceForRoomType($hotelId, $roomType);

        if (!$basePrice) {
            return null;
        }

        $estimatedTotal = $basePrice * $nights;

        return [
            'room_type' => $roomType,
            'base_price_per_night' => round($basePrice, 2),
            'nights' => $nights,
            'estimated_total' => round($estimatedTotal, 2),
            'currency' => 'MVR',
            'note' => 'This is a base estimate. Actual price may vary based on dates, view, and promotions.',
        ];
    }
}
