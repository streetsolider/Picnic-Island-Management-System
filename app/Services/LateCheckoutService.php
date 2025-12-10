<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\LateCheckoutRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LateCheckoutService
{
    /**
     * Check if there's a next booking that would conflict with late checkout
     *
     * @param HotelBooking $booking
     * @param string $requestedCheckoutTime HH:MM:SS format
     * @return array ['has_conflict' => bool, 'next_booking' => HotelBooking|null, 'next_booking_info' => array|null]
     */
    public function checkNextBookingConflict(HotelBooking $booking, string $requestedCheckoutTime): array
    {
        // Find the next booking for the same room on checkout date
        $nextBooking = HotelBooking::where('room_id', $booking->room_id)
            ->where('check_in_date', $booking->check_out_date)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->with(['guest'])
            ->first();

        if (!$nextBooking) {
            return [
                'has_conflict' => false,
                'next_booking' => null,
                'next_booking_info' => null,
            ];
        }

        // Prepare next booking info for manager's reference
        $nextBookingInfo = [
            'booking_reference' => $nextBooking->booking_reference,
            'guest_name' => $nextBooking->guest->name,
            'guest_email' => $nextBooking->guest->email,
            'check_in_date' => $nextBooking->check_in_date->format('Y-m-d'),
            'check_in_time' => $nextBooking->hotel->default_checkout_time, // Standard check-in is at checkout time
            'number_of_guests' => $nextBooking->number_of_guests,
            'room_type' => $nextBooking->room->room_type,
            'room_number' => $nextBooking->room->room_number,
        ];

        // Calculate potential conflict
        // Typically, hotels need 2-4 hours between checkout and check-in for cleaning
        $requestedCheckoutDateTime = Carbon::parse($booking->check_out_date->format('Y-m-d') . ' ' . $requestedCheckoutTime);
        $standardCheckInTime = Carbon::parse($nextBooking->check_in_date->format('Y-m-d') . ' ' . $nextBooking->hotel->default_checkout_time);

        // If late checkout extends beyond or close to next check-in, it's a potential conflict
        $hasConflict = $requestedCheckoutDateTime->greaterThan($standardCheckInTime->subHours(2));

        return [
            'has_conflict' => $hasConflict,
            'next_booking' => $nextBooking,
            'next_booking_info' => $nextBookingInfo,
        ];
    }

    /**
     * Create a late checkout request
     *
     * @param HotelBooking $booking
     * @param string $requestedCheckoutTime HH:MM:SS format
     * @param string|null $guestNotes
     * @return array ['success' => bool, 'request' => LateCheckoutRequest|null, 'errors' => array]
     */
    public function createRequest(HotelBooking $booking, string $requestedCheckoutTime, ?string $guestNotes = null): array
    {
        $errors = [];

        // Validate booking can request late checkout
        if (!$booking->canRequestLateCheckout()) {
            $errors[] = 'This booking is not eligible for late checkout request.';
        }

        // Validate requested time is after default checkout time
        $defaultCheckout = Carbon::parse($booking->hotel->default_checkout_time);
        $requestedCheckout = Carbon::parse($requestedCheckoutTime);

        if ($requestedCheckout->lessThanOrEqualTo($defaultCheckout)) {
            $errors[] = 'Requested checkout time must be after the default checkout time (' . $defaultCheckout->format('g:i A') . ').';
        }

        // Validate requested time doesn't exceed maximum (6:00 PM)
        $maxCheckout = Carbon::parse(Hotel::MAX_LATE_CHECKOUT_TIME);
        if ($requestedCheckout->greaterThan($maxCheckout)) {
            $errors[] = 'Late checkout cannot be later than ' . $maxCheckout->format('g:i A') . '.';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'request' => null,
                'errors' => $errors,
            ];
        }

        // Check for next booking
        $conflictCheck = $this->checkNextBookingConflict($booking, $requestedCheckoutTime);

        try {
            DB::beginTransaction();

            // Cancel any existing pending requests
            $booking->lateCheckoutRequest()
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            // Create new request
            $request = LateCheckoutRequest::create([
                'hotel_booking_id' => $booking->id,
                'requested_checkout_time' => $requestedCheckoutTime,
                'guest_notes' => $guestNotes,
                'has_next_booking' => $conflictCheck['next_booking'] !== null,
                'next_booking_info' => $conflictCheck['next_booking_info'],
            ]);

            DB::commit();

            return [
                'success' => true,
                'request' => $request,
                'errors' => [],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'request' => null,
                'errors' => ['Failed to create request: ' . $e->getMessage()],
            ];
        }
    }
}
