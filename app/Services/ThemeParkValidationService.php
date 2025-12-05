<?php

namespace App\Services;

use App\Models\ThemeParkActivityTicket;
use Illuminate\Support\Facades\DB;

class ThemeParkValidationService
{
    /**
     * Validate and redeem an activity ticket by QR code.
     */
    public function validateAndRedeemTicket(string $ticketReference, int $staffId): array
    {
        try {
            DB::beginTransaction();

            // Find ticket by reference (QR code)
            $ticket = ThemeParkActivityTicket::where('ticket_reference', $ticketReference)
                ->with(['activity.zone', 'showSchedule'])
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found. Invalid QR code.',
                    'error_type' => 'not_found',
                ];
            }

            // Check if ticket is already redeemed
            if ($ticket->isRedeemed()) {
                return [
                    'success' => false,
                    'message' => "This ticket was already used on {$ticket->redeemed_at->format('M j, Y \a\t g:i A')}.",
                    'error_type' => 'already_redeemed',
                    'ticket' => $ticket,
                ];
            }

            // Check if ticket is cancelled
            if ($ticket->isCancelled()) {
                return [
                    'success' => false,
                    'message' => 'This ticket has been cancelled.',
                    'error_type' => 'cancelled',
                    'ticket' => $ticket,
                ];
            }

            // Check if ticket is expired
            if ($ticket->isExpired()) {
                return [
                    'success' => false,
                    'message' => "This ticket expired on {$ticket->valid_until->format('M j, Y \a\t g:i A')}.",
                    'error_type' => 'expired',
                    'ticket' => $ticket,
                ];
            }

            // Check if activity exists and is active
            if (!$ticket->activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                    'error_type' => 'activity_not_found',
                ];
            }

            if (!$ticket->activity->is_active) {
                return [
                    'success' => false,
                    'message' => 'This activity is currently inactive.',
                    'error_type' => 'activity_inactive',
                    'ticket' => $ticket,
                ];
            }

            // Validate based on activity type
            if ($ticket->isContinuousRide()) {
                // For continuous rides, check if activity is currently open
                if (!$ticket->activity->isCurrentlyOpen()) {
                    $hours = $ticket->activity->getOperatingHoursAttribute();
                    return [
                        'success' => false,
                        'message' => "This activity is currently closed. Operating hours: {$hours}",
                        'error_type' => 'activity_closed',
                        'ticket' => $ticket,
                    ];
                }
            } elseif ($ticket->isScheduledShow()) {
                // For scheduled shows, validate show schedule
                if (!$ticket->showSchedule) {
                    return [
                        'success' => false,
                        'message' => 'Show schedule not found.',
                        'error_type' => 'schedule_not_found',
                        'ticket' => $ticket,
                    ];
                }

                if (!$ticket->showSchedule->isScheduled()) {
                    return [
                        'success' => false,
                        'message' => 'This show has been cancelled or completed.',
                        'error_type' => 'show_not_scheduled',
                        'ticket' => $ticket,
                    ];
                }

                // Check if current time is within acceptance window
                // Allow entry from 30 minutes before show time until show start
                $showDateTime = $ticket->showSchedule->show_date
                    ->setTimeFromTimeString($ticket->showSchedule->show_time);
                $now = now();
                $windowStart = $showDateTime->copy()->subMinutes(30);
                $windowEnd = $showDateTime->copy();

                if ($now->lt($windowStart)) {
                    return [
                        'success' => false,
                        'message' => "Too early. Show entry opens at {$windowStart->format('g:i A')}. Show starts at {$showDateTime->format('g:i A')}.",
                        'error_type' => 'too_early',
                        'ticket' => $ticket,
                        'show_time' => $showDateTime->format('g:i A'),
                        'entry_time' => $windowStart->format('g:i A'),
                    ];
                }

                if ($now->gt($windowEnd)) {
                    return [
                        'success' => false,
                        'message' => "Show has already started at {$showDateTime->format('g:i A')}. Entry not allowed.",
                        'error_type' => 'too_late',
                        'ticket' => $ticket,
                        'show_time' => $showDateTime->format('g:i A'),
                    ];
                }
            }

            // All validations passed - redeem the ticket
            $ticket->redeem($staffId);

            DB::commit();

            return [
                'success' => true,
                'message' => "Ticket validated successfully! Access granted for {$ticket->quantity} " . str_plural('person', $ticket->quantity) . ".",
                'ticket' => $ticket->fresh(['activity', 'showSchedule']),
                'activity' => [
                    'name' => $ticket->activity->name,
                    'type' => $ticket->activity->activity_type,
                    'zone' => $ticket->activity->zone->name ?? null,
                ],
                'show_info' => $ticket->isScheduledShow() && $ticket->showSchedule ? [
                    'date' => $ticket->showSchedule->show_date->format('F j, Y'),
                    'time' => $ticket->showSchedule->show_time,
                ] : null,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'error_type' => 'system_error',
            ];
        }
    }

    /**
     * Check ticket status without redeeming (read-only validation).
     */
    public function checkTicketStatus(string $ticketReference): array
    {
        $ticket = ThemeParkActivityTicket::where('ticket_reference', $ticketReference)
            ->with(['activity.zone', 'showSchedule', 'user'])
            ->first();

        if (!$ticket) {
            return [
                'success' => false,
                'message' => 'Ticket not found.',
                'error_type' => 'not_found',
            ];
        }

        // Determine ticket status
        $status = 'valid';
        $statusMessage = 'Valid';

        if ($ticket->isRedeemed()) {
            $status = 'redeemed';
            $statusMessage = "Used on {$ticket->redeemed_at->format('M j, Y \a\t g:i A')}";
        } elseif ($ticket->isCancelled()) {
            $status = 'cancelled';
            $statusMessage = 'Cancelled';
        } elseif ($ticket->isExpired()) {
            $status = 'expired';
            $statusMessage = "Expired on {$ticket->valid_until->format('M j, Y \a\t g:i A')}";
        }

        return [
            'success' => true,
            'ticket' => $ticket,
            'status' => $status,
            'status_message' => $statusMessage,
            'activity' => [
                'name' => $ticket->activity->name ?? 'Unknown',
                'type' => $ticket->activity->activity_type ?? null,
                'zone' => $ticket->activity->zone->name ?? null,
                'is_active' => $ticket->activity->is_active ?? false,
            ],
            'show_info' => $ticket->isScheduledShow() && $ticket->showSchedule ? [
                'date' => $ticket->showSchedule->show_date->format('F j, Y'),
                'time' => $ticket->showSchedule->show_time,
                'status' => $ticket->showSchedule->status,
            ] : null,
            'customer' => [
                'name' => $ticket->user->name ?? 'Unknown',
            ],
            'purchase_info' => [
                'purchased_at' => $ticket->purchase_datetime->format('M j, Y \a\t g:i A'),
                'quantity' => $ticket->quantity,
                'credits_spent' => $ticket->credits_spent,
            ],
        ];
    }

    /**
     * Get statistics for operator dashboard.
     */
    public function getOperatorStats(int $staffId, ?string $date = null): array
    {
        $date = $date ? \Carbon\Carbon::parse($date) : now();

        // Get tickets validated by this staff member today
        $validatedToday = ThemeParkActivityTicket::where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->count();

        // Get total persons admitted today
        $personsToday = ThemeParkActivityTicket::where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->sum('quantity');

        // Get breakdown by activity type
        $continuousRidesValidated = ThemeParkActivityTicket::where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->whereNull('show_schedule_id')
            ->count();

        $showTicketsValidated = ThemeParkActivityTicket::where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->whereNotNull('show_schedule_id')
            ->count();

        return [
            'date' => $date->format('F j, Y'),
            'tickets_validated' => $validatedToday,
            'total_persons_admitted' => $personsToday,
            'continuous_rides' => $continuousRidesValidated,
            'scheduled_shows' => $showTicketsValidated,
        ];
    }

    /**
     * Get recent validation history for operator.
     */
    public function getRecentValidations(int $staffId, int $limit = 20)
    {
        return ThemeParkActivityTicket::where('redeemed_by_staff_id', $staffId)
            ->with(['activity', 'showSchedule', 'user'])
            ->orderBy('redeemed_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
