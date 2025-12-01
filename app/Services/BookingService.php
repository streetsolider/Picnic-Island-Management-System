<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    protected PricingCalculator $pricingCalculator;

    public function __construct(PricingCalculator $pricingCalculator)
    {
        $this->pricingCalculator = $pricingCalculator;
    }

    /**
     * Check room availability for given dates
     *
     * @param Hotel $hotel
     * @param string $checkIn
     * @param string $checkOut
     * @param int $guests Number of guests
     * @param string|null $roomType
     * @param string|null $view
     * @param string|null $bedSize
     * @param string|null $bedCount
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableRooms(
        Hotel $hotel,
        string $checkIn,
        string $checkOut,
        int $guests = 1,
        ?string $roomType = null,
        ?string $view = null,
        ?string $bedSize = null,
        ?string $bedCount = null,
        ?float $minPrice = null,
        ?float $maxPrice = null
    ) {
        $query = $hotel->rooms()
            ->availableForDates($checkIn, $checkOut)
            ->where('max_occupancy', '>=', $guests); // Filter by guest capacity

        if ($roomType) {
            $query->where('room_type', $roomType);
        }

        if ($view) {
            $query->where('view', $view);
        }

        if ($bedSize) {
            $query->where('bed_size', $bedSize);
        }

        if ($bedCount) {
            $query->where('bed_count', $bedCount);
        }

        $rooms = $query->get();

        // Apply price filtering if specified
        if ($minPrice !== null || $maxPrice !== null) {
            $checkInCarbon = Carbon::parse($checkIn);
            $checkOutCarbon = Carbon::parse($checkOut);

            $rooms = $rooms->filter(function ($room) use ($checkInCarbon, $checkOutCarbon, $minPrice, $maxPrice) {
                $pricing = $this->pricingCalculator->calculateRoomPrice(
                    $room,
                    $checkInCarbon,
                    $checkOutCarbon
                );

                $totalPrice = $pricing['total_price'];

                if ($minPrice !== null && $totalPrice < $minPrice) {
                    return false;
                }

                if ($maxPrice !== null && $totalPrice > $maxPrice) {
                    return false;
                }

                return true;
            });
        }

        return $rooms;
    }

    /**
     * Validate booking data
     *
     * @param array $data
     * @return array Array with 'valid' boolean and 'errors' array
     */
    public function validateBooking(array $data): array
    {
        $errors = [];

        // Validate dates
        $checkIn = Carbon::parse($data['check_in_date']);
        $checkOut = Carbon::parse($data['check_out_date']);
        $today = Carbon::today();

        if ($checkIn->lt($today)) {
            $errors[] = 'Check-in date cannot be in the past.';
        }

        if ($checkOut->lte($checkIn)) {
            $errors[] = 'Check-out date must be after check-in date.';
        }

        if ($checkIn->diffInDays($checkOut) < 1) {
            $errors[] = 'Minimum stay is 1 night.';
        }

        // Validate room availability
        $room = Room::find($data['room_id']);
        if (!$room) {
            $errors[] = 'Selected room not found.';
        } elseif (!$room->isAvailableForDates($data['check_in_date'], $data['check_out_date'])) {
            $errors[] = 'Selected room is not available for the chosen dates.';
        }

        // Validate guest capacity
        if ($room && $data['number_of_guests'] > $room->max_occupancy) {
            $errors[] = "Room can accommodate maximum {$room->max_occupancy} guests.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Create a new booking
     *
     * @param array $data
     * @return HotelBooking
     * @throws Exception
     */
    public function createBooking(array $data): HotelBooking
    {
        // Validate booking
        $validation = $this->validateBooking($data);
        if (!$validation['valid']) {
            throw new Exception('Booking validation failed: ' . implode(', ', $validation['errors']));
        }

        $room = Room::findOrFail($data['room_id']);

        // Calculate price
        $pricing = $this->pricingCalculator->calculateRoomPrice(
            $room,
            Carbon::parse($data['check_in_date']),
            Carbon::parse($data['check_out_date']),
            $data['number_of_rooms'] ?? 1,
            $data['promo_code'] ?? null,
            $this->calculateAdvanceDays($data['check_in_date'])
        );

        // Create booking in a transaction
        return DB::transaction(function () use ($data, $room, $pricing) {
            $booking = HotelBooking::create([
                'hotel_id' => $room->hotel_id,
                'room_id' => $room->id,
                'guest_id' => $data['guest_id'],
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'number_of_guests' => $data['number_of_guests'],
                'number_of_rooms' => $data['number_of_rooms'] ?? 1,
                'total_price' => $pricing['total_price'],
                'payment_status' => $data['payment_status'] ?? 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'promo_code' => $data['promo_code'] ?? null,
                'special_requests' => $data['special_requests'] ?? null,
                'status' => 'confirmed', // Automatic confirmation
            ]);

            return $booking;
        });
    }

    /**
     * Update an existing booking
     *
     * @param HotelBooking $booking
     * @param array $data
     * @return HotelBooking
     * @throws Exception
     */
    public function updateBooking(HotelBooking $booking, array $data): HotelBooking
    {
        if ($booking->isCancelled() || $booking->isCompleted()) {
            throw new Exception('Cannot update a cancelled or completed booking.');
        }

        // If dates or room are changing, validate availability
        if (
            isset($data['check_in_date']) ||
            isset($data['check_out_date']) ||
            isset($data['room_id'])
        ) {
            $checkIn = $data['check_in_date'] ?? $booking->check_in_date->format('Y-m-d');
            $checkOut = $data['check_out_date'] ?? $booking->check_out_date->format('Y-m-d');
            $roomId = $data['room_id'] ?? $booking->room_id;

            $room = Room::findOrFail($roomId);

            if (!$room->isAvailableForDates($checkIn, $checkOut, $booking->id)) {
                throw new Exception('Room is not available for the selected dates.');
            }

            // Recalculate price if dates changed
            $pricing = $this->pricingCalculator->calculateRoomPrice(
                $room,
                Carbon::parse($checkIn),
                Carbon::parse($checkOut),
                $data['number_of_rooms'] ?? $booking->number_of_rooms,
                $data['promo_code'] ?? $booking->promo_code,
                $this->calculateAdvanceDays($checkIn)
            );

            $data['total_price'] = $pricing['total_price'];
        }

        $booking->update($data);
        return $booking->fresh();
    }

    /**
     * Cancel a booking
     *
     * @param HotelBooking $booking
     * @param string|null $reason
     * @return bool
     */
    public function cancelBooking(HotelBooking $booking, ?string $reason = null): bool
    {
        return $booking->cancel($reason);
    }

    /**
     * Get booking statistics for a hotel
     *
     * @param Hotel $hotel
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getBookingStats(Hotel $hotel, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = $hotel->bookings();

        if ($startDate) {
            $query->where('check_in_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('check_out_date', '<=', $endDate);
        }

        $totalBookings = (clone $query)->count();
        $confirmedBookings = (clone $query)->where('status', 'confirmed')->count();
        $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
        $totalRevenue = (clone $query)->where('payment_status', 'paid')->sum('total_price');

        return [
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'total_revenue' => $totalRevenue,
        ];
    }

    /**
     * Calculate number of days in advance for booking
     *
     * @param string $checkInDate
     * @return int
     */
    protected function calculateAdvanceDays(string $checkInDate): int
    {
        $checkIn = Carbon::parse($checkInDate);
        $now = Carbon::now();

        return max(0, $now->diffInDays($checkIn, false));
    }

    /**
     * Get occupancy rate for a hotel
     *
     * @param Hotel $hotel
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getOccupancyRate(Hotel $hotel, string $startDate, string $endDate): float
    {
        $totalRooms = $hotel->rooms()->where('is_active', true)->count();

        if ($totalRooms === 0) {
            return 0;
        }

        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $totalRoomNights = $totalRooms * $totalDays;

        if ($totalRoomNights === 0) {
            return 0;
        }

        // Count booked room nights
        $bookedRoomNights = $hotel->bookings()
            ->where('status', 'confirmed')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('check_in_date', '<', $endDate)
                  ->where('check_out_date', '>', $startDate);
            })
            ->get()
            ->sum(function ($booking) use ($startDate, $endDate) {
                $bookingStart = max(Carbon::parse($booking->check_in_date), Carbon::parse($startDate));
                $bookingEnd = min(Carbon::parse($booking->check_out_date), Carbon::parse($endDate));
                return $bookingStart->diffInDays($bookingEnd);
            });

        return ($bookedRoomNights / $totalRoomNights) * 100;
    }
}
