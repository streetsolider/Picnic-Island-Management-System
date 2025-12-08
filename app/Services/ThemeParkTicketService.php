<?php

namespace App\Services;

use App\Models\HotelBooking;
use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivityTicket;
use App\Models\ThemeParkShowSchedule;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThemeParkTicketService
{
    /**
     * Validate guest has valid hotel booking (CRITICAL - same as beach/ferry)
     *
     * @param int $guestId
     * @return array ['valid' => bool, 'booking' => HotelBooking|null, 'errors' => array]
     */
    public function validateHotelBooking(int $guestId): array
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
                'errors' => ['Valid hotel booking required to purchase tickets.'],
            ];
        }

        return [
            'valid' => true,
            'booking' => $booking,
            'errors' => [],
        ];
    }


    /**
     * Purchase activity ticket for continuous ride.
     */
    public function purchaseContinuousRideTicket(int $userId, int $activityId, int $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            // Validate hotel booking
            $hotelValidation = $this->validateHotelBooking($userId);
            if (!$hotelValidation['valid']) {
                return [
                    'success' => false,
                    'message' => implode(' ', $hotelValidation['errors']),
                ];
            }

            $hotelBooking = $hotelValidation['booking'];

            // Validate quantity
            if ($quantity < 1) {
                return [
                    'success' => false,
                    'message' => 'Quantity must be at least 1.',
                ];
            }

            // Get activity
            $activity = ThemeParkActivity::with('zone')->find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            // Verify it's a continuous ride
            if (!$activity->isContinuous()) {
                return [
                    'success' => false,
                    'message' => 'This is a scheduled show, not a continuous ride.',
                ];
            }

            if (!$activity->is_active) {
                return [
                    'success' => false,
                    'message' => 'Activity is currently inactive.',
                ];
            }

            // Check if activity is within operating hours (optional validation)
            if (!$activity->isCurrentlyOpen()) {
                return [
                    'success' => false,
                    'message' => "Activity is closed. Hours: {$activity->getOperatingHoursAttribute()}",
                ];
            }

            // CHECKOUT DAY VALIDATION: For continuous rides, check if ride STARTS before checkout time
            if (now()->isSameDay($hotelBooking->check_out_date)) {
                $checkoutTime = $hotelBooking->getEffectiveCheckoutTime();

                if ($activity->operating_hours_start) {
                    $rideStartTime = Carbon::parse(now()->toDateString() . ' ' . $activity->operating_hours_start->format('H:i:s'));

                    if ($rideStartTime->greaterThanOrEqualTo($checkoutTime)) {
                        return [
                            'success' => false,
                            'message' => "Ride starts at {$rideStartTime->format('g:i A')}, after your checkout time ({$checkoutTime->format('g:i A')}).",
                        ];
                    }
                }
            }

            // Calculate total credits needed
            $totalCreditsNeeded = $activity->credit_cost * $quantity;

            // Get wallet
            $wallet = ThemeParkWallet::getOrCreateForUser($userId);

            // Check if user has sufficient credits
            if (!$wallet->hasSufficientCredits($totalCreditsNeeded)) {
                $needed = $totalCreditsNeeded - $wallet->credit_balance;
                return [
                    'success' => false,
                    'message' => "Need {$needed} more credits. (Total: {$totalCreditsNeeded}, Balance: {$wallet->credit_balance})",
                ];
            }

            // Create activity ticket
            $ticket = ThemeParkActivityTicket::create([
                'guest_id' => $userId,
                'activity_id' => $activityId,
                'show_schedule_id' => null, // Continuous ride - no schedule
                'credits_spent' => $totalCreditsNeeded,
                'quantity' => $quantity,
                'total_credits_paid' => $totalCreditsNeeded,
                'status' => 'valid',
                'purchase_datetime' => now(),
                'valid_until' => now()->endOfDay(), // Valid until end of day
            ]);

            // Deduct credits from wallet
            $wallet->credit_balance -= $totalCreditsNeeded;
            $wallet->total_credits_redeemed += $totalCreditsNeeded;
            $wallet->save();

            // Create wallet transaction
            ThemeParkWalletTransaction::create([
                'user_id' => $userId,
                'activity_ticket_id' => $ticket->id,
                'transaction_type' => 'activity_ticket_purchase',
                'credits_amount' => $totalCreditsNeeded,
                'balance_before_mvr' => $wallet->balance_mvr,
                'balance_after_mvr' => $wallet->balance_mvr,
                'balance_before_credits' => $wallet->credit_balance + $totalCreditsNeeded,
                'balance_after_credits' => $wallet->credit_balance,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully purchased ticket for {$quantity} " . Str::plural('person', $quantity) . " ({$activity->name}). Show QR code to operator.",
                'ticket' => $ticket->load('activity'),
                'qr_code' => $ticket->ticket_reference,
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to purchase ticket: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Purchase activity ticket for scheduled show.
     */
    public function purchaseShowTicket(int $userId, int $activityId, int $showScheduleId, int $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            // Validate quantity
            if ($quantity < 1) {
                return [
                    'success' => false,
                    'message' => 'Quantity must be at least 1.',
                ];
            }

            // Get activity
            $activity = ThemeParkActivity::find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            // Verify it's a scheduled show
            if (!$activity->isScheduled()) {
                return [
                    'success' => false,
                    'message' => 'This is a continuous ride, not a scheduled show.',
                ];
            }

            if (!$activity->is_active) {
                return [
                    'success' => false,
                    'message' => 'Activity is currently inactive.',
                ];
            }

            // Get show schedule
            $showSchedule = ThemeParkShowSchedule::find($showScheduleId);

            if (!$showSchedule) {
                return [
                    'success' => false,
                    'message' => 'Show schedule not found.',
                ];
            }

            // Validate hotel booking
            $hotelValidation = $this->validateHotelBooking($userId);
            if (!$hotelValidation['valid']) {
                return [
                    'success' => false,
                    'message' => implode(' ', $hotelValidation['errors']),
                ];
            }

            $hotelBooking = $hotelValidation['booking'];

            // CHECKOUT DAY VALIDATION: Shows cannot start at or after checkout time
            if ($showSchedule->show_date->isSameDay($hotelBooking->check_out_date)) {
                $checkoutTime = $hotelBooking->getEffectiveCheckoutTime();
                $showStartTime = Carbon::parse($showSchedule->show_date->toDateString() . ' ' . $showSchedule->show_time);

                if ($showStartTime->greaterThanOrEqualTo($checkoutTime)) {
                    return [
                        'success' => false,
                        'message' => "Show starts at {$showStartTime->format('g:i A')}, after your checkout time ({$checkoutTime->format('g:i A')}).",
                    ];
                }
            }

            if ($showSchedule->activity_id !== $activityId) {
                return [
                    'success' => false,
                    'message' => 'Show schedule does not match the selected activity.',
                ];
            }

            if (!$showSchedule->isScheduled()) {
                return [
                    'success' => false,
                    'message' => 'Show is cancelled or completed.',
                ];
            }

            // Check capacity
            $availableSeats = $showSchedule->getRemainingSeats();
            if ($quantity > $availableSeats) {
                return [
                    'success' => false,
                    'message' => "Only {$availableSeats} " . ($availableSeats === 1 ? 'seat' : 'seats') . " available.",
                ];
            }

            // Calculate total credits needed
            $totalCreditsNeeded = $activity->credit_cost * $quantity;

            // Get wallet
            $wallet = ThemeParkWallet::getOrCreateForUser($userId);

            // Check if user has sufficient credits
            if (!$wallet->hasSufficientCredits($totalCreditsNeeded)) {
                $needed = $totalCreditsNeeded - $wallet->credit_balance;
                return [
                    'success' => false,
                    'message' => "Need {$needed} more credits. (Total: {$totalCreditsNeeded}, Balance: {$wallet->credit_balance})",
                ];
            }

            // Create activity ticket
            // For scheduled shows, valid until = show start time + duration
            $showStartTime = $showSchedule->show_date->setTimeFromTimeString($showSchedule->show_time);
            $validUntil = $showStartTime->copy()->addMinutes($activity->duration_minutes ?? 60);

            $ticket = ThemeParkActivityTicket::create([
                'guest_id' => $userId,
                'activity_id' => $activityId,
                'show_schedule_id' => $showScheduleId,
                'credits_spent' => $totalCreditsNeeded,
                'quantity' => $quantity,
                'total_credits_paid' => $totalCreditsNeeded,
                'status' => 'valid',
                'purchase_datetime' => now(),
                'valid_until' => $validUntil,
            ]);

            // Increment tickets sold
            $showSchedule->incrementTicketsSold($quantity);

            // Deduct credits from wallet
            $wallet->credit_balance -= $totalCreditsNeeded;
            $wallet->total_credits_redeemed += $totalCreditsNeeded;
            $wallet->save();

            // Create wallet transaction
            ThemeParkWalletTransaction::create([
                'user_id' => $userId,
                'activity_ticket_id' => $ticket->id,
                'transaction_type' => 'activity_ticket_purchase',
                'credits_amount' => $totalCreditsNeeded,
                'balance_before_mvr' => $wallet->balance_mvr,
                'balance_after_mvr' => $wallet->balance_mvr,
                'balance_before_credits' => $wallet->credit_balance + $totalCreditsNeeded,
                'balance_after_credits' => $wallet->credit_balance,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully purchased show ticket for {$quantity} " . Str::plural('person', $quantity) . " ({$activity->name} at {$showSchedule->show_time}).",
                'ticket' => $ticket->load(['activity', 'showSchedule']),
                'qr_code' => $ticket->ticket_reference,
                'show_info' => [
                    'date' => $showSchedule->show_date->format('F j, Y'),
                    'time' => $showSchedule->show_time,
                    'venue_capacity' => $showSchedule->venue_capacity,
                    'tickets_sold' => $showSchedule->tickets_sold,
                ],
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to purchase show ticket: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get user's activity tickets.
     */
    public function getUserTickets(int $userId, ?string $status = null)
    {
        $query = ThemeParkActivityTicket::where('guest_id', $userId)
            ->with(['activity', 'showSchedule'])
            ->orderBy('purchase_datetime', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Cancel an activity ticket.
     */
    public function cancelTicket(int $ticketId, int $userId, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $ticket = ThemeParkActivityTicket::where('id', $ticketId)
                ->where('guest_id', $userId)
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
                ];
            }

            if ($ticket->isRedeemed()) {
                return [
                    'success' => false,
                    'message' => 'Cannot cancel a ticket that has already been redeemed.',
                ];
            }

            if ($ticket->isCancelled()) {
                return [
                    'success' => false,
                    'message' => 'This ticket is already cancelled.',
                ];
            }

            // Refund credits to wallet
            $wallet = ThemeParkWallet::getOrCreateForUser($userId);
            $wallet->credit_balance += $ticket->credits_spent;
            $wallet->total_credits_redeemed -= $ticket->credits_spent;
            $wallet->save();

            // Cancel ticket (this also decrements show tickets_sold if applicable)
            $ticket->cancel($reason);

            DB::commit();

            return [
                'success' => true,
                'message' => "Ticket cancelled successfully. {$ticket->credits_spent} credits refunded.",
                'ticket' => $ticket->fresh(),
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to cancel ticket: ' . $e->getMessage(),
            ];
        }
    }
}
