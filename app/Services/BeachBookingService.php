<?php

namespace App\Services;

use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use App\Models\HotelBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BeachBookingService
{
    /**
     * Validate guest has valid hotel booking (CRITICAL - same as ferry tickets)
     *
     * @param int $guestId
     * @param string|null $activityDate Date of the activity in Y-m-d format
     * @return array ['valid' => bool, 'booking' => HotelBooking|null, 'errors' => array]
     */
    public function validateHotelBooking(int $guestId, ?string $activityDate = null): array
    {
        $booking = HotelBooking::where('guest_id', $guestId)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>=', now()->toDateString())
            ->with(['hotel', 'lateCheckoutRequest'])
            ->first();

        if (!$booking) {
            return [
                'valid' => false,
                'booking' => null,
                'errors' => ['No valid hotel booking found. You must have a confirmed or checked-in hotel booking to book beach activities.'],
            ];
        }

        // Note: Checkout day validation is handled in validateBooking() method
        // where we have access to activity start/end times

        return [
            'valid' => true,
            'booking' => $booking,
            'errors' => [],
        ];
    }

    /**
     * Get available time slots for a service on a specific date
     *
     * @param BeachService $service
     * @param string $date Date in Y-m-d format
     * @param HotelBooking|null $hotelBooking Optional hotel booking for checkout validation
     * @return array
     */
    public function getAvailableSlots(BeachService $service, string $date, ?HotelBooking $hotelBooking = null): array
    {
        $date = Carbon::parse($date);
        $isToday = $date->isToday();

        // After closing time rule: if today and past closing, return empty
        if ($isToday && $service->closing_time && now()->greaterThan($service->closing_time)) {
            return [];
        }

        if ($service->isFixedSlot()) {
            return $this->generateFixedSlots($service, $date, $hotelBooking);
        } else {
            return $this->generateFlexibleSlots($service, $date, $hotelBooking);
        }
    }

    /**
     * Generate fixed time slots
     *
     * @param BeachService $service
     * @param Carbon $date
     * @param HotelBooking|null $hotelBooking
     * @return array
     */
    protected function generateFixedSlots(BeachService $service, Carbon $date, ?HotelBooking $hotelBooking = null): array
    {
        $slots = [];

        if (!$service->opening_time || !$service->closing_time || !$service->slot_duration_minutes) {
            return $slots;
        }

        $currentSlot = Carbon::parse($service->opening_time);
        $closingTime = Carbon::parse($service->closing_time);
        $now = now();
        $isToday = $date->isToday();

        // Check if this is checkout day
        $isCheckoutDay = $hotelBooking && $date->isSameDay($hotelBooking->check_out_date);
        $checkoutTime = $isCheckoutDay ? $hotelBooking->getEffectiveCheckoutTime() : null;

        while ($currentSlot->lessThan($closingTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($service->slot_duration_minutes);

            // Skip past slots if today
            if ($isToday && $currentSlot->lessThanOrEqualTo($now)) {
                $currentSlot = $slotEnd;
                continue;
            }

            // Skip slots that would end after checkout time on checkout day
            if ($isCheckoutDay) {
                $slotEndWithDate = Carbon::parse($date->toDateString() . ' ' . $slotEnd->format('H:i:s'));
                if ($slotEndWithDate->greaterThan($checkoutTime)) {
                    $currentSlot = $slotEnd;
                    continue;
                }
            }

            // Check if slot end exceeds closing time
            if ($slotEnd->greaterThan($closingTime)) {
                break;
            }

            $startTime = $currentSlot->format('H:i:s');
            $endTime = $slotEnd->format('H:i:s');

            // Check availability
            $available = $service->isAvailable($date->toDateString(), $startTime, $endTime);

            // Add all slots (both available and unavailable) for display purposes
            $slots[] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'time' => $currentSlot->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'start_time_formatted' => $currentSlot->format('g:i A'),
                'end_time_formatted' => $slotEnd->format('g:i A'),
                'price' => (float) $service->slot_price,
                'available' => $available,
            ];

            $currentSlot = $slotEnd;
        }

        return $slots;
    }

    /**
     * Generate flexible duration slots (available start times)
     *
     * @param BeachService $service
     * @param Carbon $date
     * @param HotelBooking|null $hotelBooking
     * @return array
     */
    protected function generateFlexibleSlots(BeachService $service, Carbon $date, ?HotelBooking $hotelBooking = null): array
    {
        $slots = [];

        if (!$service->opening_time || !$service->closing_time) {
            return $slots;
        }

        $currentTime = Carbon::parse($service->opening_time);
        $closingTime = Carbon::parse($service->closing_time);
        $now = now();
        $isToday = $date->isToday();

        // Generate hourly start times
        while ($currentTime->lessThan($closingTime)) {
            // Skip past times if today
            if ($isToday && $currentTime->lessThanOrEqualTo($now)) {
                $currentTime->addHour();
                continue;
            }

            $slots[] = [
                'start_time' => $currentTime->format('H:i:s'),
                'start_time_formatted' => $currentTime->format('g:i A'),
                'price_per_hour' => (float) $service->price_per_hour,
                'max_duration_hours' => $currentTime->diffInHours($closingTime),
            ];

            $currentTime->addHour();
        }

        return $slots;
    }

    /**
     * Calculate price for a booking
     *
     * @param BeachService $service
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function calculatePrice(BeachService $service, string $startTime, string $endTime): array
    {
        if ($service->isFixedSlot()) {
            return [
                'price_per_unit' => (float) $service->slot_price,
                'total_price' => (float) $service->slot_price,
                'breakdown' => "1 slot × MVR {$service->slot_price}",
                'duration_hours' => null,
            ];
        } else {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $hours = $start->diffInHours($end);

            return [
                'price_per_unit' => (float) $service->price_per_hour,
                'total_price' => $hours * (float) $service->price_per_hour,
                'breakdown' => "{$hours} hours × MVR {$service->price_per_hour}/hour",
                'duration_hours' => $hours,
            ];
        }
    }

    /**
     * Validate booking request
     *
     * @param array $data
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateBooking(array $data): array
    {
        $errors = [];

        // Check required fields
        $requiredFields = ['guest_id', 'beach_service_id', 'booking_date', 'start_time', 'end_time'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = "The {$field} field is required.";
            }
        }

        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate service exists and is active
        $service = BeachService::find($data['beach_service_id']);
        if (!$service) {
            $errors[] = 'Beach service not found.';
            return ['valid' => false, 'errors' => $errors];
        }

        if (!$service->is_active) {
            $errors[] = 'This service is currently inactive.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate hotel booking with activity date
        $hotelValidation = $this->validateHotelBooking($data['guest_id'], $data['booking_date']);
        if (!$hotelValidation['valid']) {
            return $hotelValidation;
        }

        $hotelBooking = $hotelValidation['booking'];

        // Check if booking date is within hotel stay
        $bookingDate = Carbon::parse($data['booking_date']);
        if ($bookingDate->lessThan($hotelBooking->check_in_date) ||
            $bookingDate->greaterThan($hotelBooking->check_out_date)) {
            $errors[] = 'Booking date must be within your hotel stay period.';
            return ['valid' => false, 'errors' => $errors];
        }

        // CHECKOUT DAY VALIDATION: Activities can be booked on checkout day
        // but must END before checkout time
        if ($bookingDate->isSameDay($hotelBooking->check_out_date)) {
            $checkoutTime = $hotelBooking->getEffectiveCheckoutTime();
            $activityEndTime = Carbon::parse($data['booking_date'] . ' ' . $data['end_time']);

            if ($activityEndTime->greaterThan($checkoutTime)) {
                $checkoutTimeFormatted = $checkoutTime->format('g:i A');
                $activityEndFormatted = $activityEndTime->format('g:i A');
                $errors[] = "Beach activities on your checkout day ({$hotelBooking->check_out_date->format('M d, Y')}) " .
                           "must end before your checkout time of {$checkoutTimeFormatted}. " .
                           "Your selected activity ends at {$activityEndFormatted}. " .
                           "Please choose an earlier time or a different date.";
                return ['valid' => false, 'errors' => $errors];
            }
        }

        // Validate booking date is not in the past
        if ($bookingDate->lessThan(now()->toDateString())) {
            $errors[] = 'Booking date cannot be in the past.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Check if service is available at the requested time
        if (!$service->isAvailable($data['booking_date'], $data['start_time'], $data['end_time'])) {
            $errors[] = 'This time slot is not available. Please choose another time.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate times are within operating hours
        if ($service->opening_time && $service->closing_time) {
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $openingTime = Carbon::parse($service->opening_time);
            $closingTime = Carbon::parse($service->closing_time);

            if ($startTime->lessThan($openingTime) || $endTime->greaterThan($closingTime)) {
                $errors[] = 'Booking times must be within service operating hours.';
                return ['valid' => false, 'errors' => $errors];
            }
        }

        // After closing time rule for today
        if ($bookingDate->isToday() && $service->closing_time && now()->greaterThan($service->closing_time)) {
            $errors[] = 'Service is closed for today. Please book for tomorrow or later.';
            return ['valid' => false, 'errors' => $errors];
        }

        return [
            'valid' => true,
            'errors' => [],
            'hotel_booking' => $hotelBooking,
        ];
    }

    /**
     * Create a beach service booking
     *
     * @param array $data
     * @return BeachServiceBooking
     * @throws \Exception
     */
    public function createBooking(array $data): BeachServiceBooking
    {
        return DB::transaction(function () use ($data) {
            // Validate booking
            $validation = $this->validateBooking($data);

            if (!$validation['valid']) {
                throw new \Exception(implode(' ', $validation['errors']));
            }

            $service = BeachService::findOrFail($data['beach_service_id']);
            $hotelBooking = $validation['hotel_booking'];

            // Calculate price
            $pricing = $this->calculatePrice($service, $data['start_time'], $data['end_time']);

            // Create booking
            $booking = BeachServiceBooking::create([
                'guest_id' => $data['guest_id'],
                'beach_service_id' => $data['beach_service_id'],
                'hotel_booking_id' => $hotelBooking->id,
                'booking_date' => $data['booking_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'duration_hours' => $pricing['duration_hours'],
                'price_per_unit' => $pricing['price_per_unit'],
                'total_price' => $pricing['total_price'],
                'status' => 'confirmed',
                'payment_status' => $data['payment_status'] ?? 'pending',
            ]);

            return $booking;
        });
    }

    /**
     * Get quick price estimate for a service
     *
     * @param BeachService $service
     * @param int $hours For flexible duration
     * @return array
     */
    public function getQuickEstimate(BeachService $service, int $hours = 1): array
    {
        if ($service->isFixedSlot()) {
            return [
                'price' => (float) $service->slot_price,
                'description' => "MVR {$service->slot_price} per slot",
            ];
        } else {
            $total = $hours * (float) $service->price_per_hour;
            return [
                'price' => $total,
                'description' => "MVR {$service->price_per_hour}/hour × {$hours} hours = MVR {$total}",
            ];
        }
    }
}
