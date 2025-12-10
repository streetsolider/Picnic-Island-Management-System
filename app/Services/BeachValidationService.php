<?php

namespace App\Services;

use App\Models\BeachServiceBooking;
use Illuminate\Support\Facades\DB;

class BeachValidationService
{
    /**
     * Validate and redeem a booking by reference code
     *
     * @param string $bookingReference
     * @param int $staffId
     * @return array
     */
    public function validateAndRedeemBooking(string $bookingReference, int $staffId): array
    {
        try {
            DB::beginTransaction();

            // Find booking by reference
            $booking = BeachServiceBooking::where('booking_reference', $bookingReference)
                ->with(['service', 'guest', 'hotelBooking'])
                ->first();

            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Booking not found. Invalid reference code.',
                    'error_type' => 'not_found',
                ];
            }

            // Check if booking is already redeemed
            if ($booking->isRedeemed()) {
                return [
                    'success' => false,
                    'message' => "This booking was already used on {$booking->redeemed_at->format('M j, Y \a\t g:i A')}.",
                    'error_type' => 'already_redeemed',
                    'booking' => $booking,
                ];
            }

            // Check if booking is cancelled
            if ($booking->isCancelled()) {
                return [
                    'success' => false,
                    'message' => 'This booking has been cancelled.',
                    'error_type' => 'cancelled',
                    'booking' => $booking,
                ];
            }

            // Check if booking is expired
            if ($booking->isExpired()) {
                return [
                    'success' => false,
                    'message' => 'This booking has expired.',
                    'error_type' => 'expired',
                    'booking' => $booking,
                ];
            }

            // Check if service exists and is active
            if (!$booking->service) {
                return [
                    'success' => false,
                    'message' => 'Beach service not found.',
                    'error_type' => 'service_not_found',
                ];
            }

            if (!$booking->service->is_active) {
                return [
                    'success' => false,
                    'message' => 'This service is currently inactive.',
                    'error_type' => 'service_inactive',
                    'booking' => $booking,
                ];
            }

            // Check booking is for TODAY
            if (!$booking->booking_date->isToday()) {
                $dateStr = $booking->booking_date->format('M j, Y');
                if ($booking->booking_date->isFuture()) {
                    return [
                        'success' => false,
                        'message' => "This booking is for {$dateStr}. Too early to redeem.",
                        'error_type' => 'wrong_date',
                        'booking' => $booking,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => "This booking was for {$dateStr}. It has expired.",
                        'error_type' => 'expired_date',
                        'booking' => $booking,
                    ];
                }
            }

            // All validations passed - redeem the booking
            $booking->redeem($staffId);

            DB::commit();

            return [
                'success' => true,
                'message' => "Booking validated successfully! Access granted for {$booking->service->name}.",
                'booking' => $booking->fresh(['service', 'guest', 'hotelBooking']),
                'service' => [
                    'name' => $booking->service->name,
                    'category' => $booking->service->category->name ?? 'N/A',
                ],
                'time_info' => [
                    'start' => $booking->start_time,
                    'end' => $booking->end_time,
                    'duration_hours' => $booking->duration_hours,
                ],
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
     * Check booking status without redeeming (read-only validation)
     *
     * @param string $bookingReference
     * @return array
     */
    public function checkBookingStatus(string $bookingReference): array
    {
        $booking = BeachServiceBooking::where('booking_reference', $bookingReference)
            ->with(['service.category', 'guest', 'hotelBooking'])
            ->first();

        if (!$booking) {
            return [
                'success' => false,
                'message' => 'Booking not found.',
                'error_type' => 'not_found',
            ];
        }

        // Determine booking status
        $status = 'valid';
        $statusMessage = 'Valid - Ready to redeem';

        if ($booking->isRedeemed()) {
            $status = 'redeemed';
            $statusMessage = "Used on {$booking->redeemed_at->format('M j, Y \a\t g:i A')}";
        } elseif ($booking->isCancelled()) {
            $status = 'cancelled';
            $statusMessage = 'Cancelled';
        } elseif ($booking->isExpired()) {
            $status = 'expired';
            $statusMessage = 'Expired';
        } elseif (!$booking->booking_date->isToday()) {
            if ($booking->booking_date->isFuture()) {
                $status = 'future';
                $statusMessage = "Scheduled for {$booking->booking_date->format('M j, Y')}";
            } else {
                $status = 'past';
                $statusMessage = "Was scheduled for {$booking->booking_date->format('M j, Y')}";
            }
        }

        return [
            'success' => true,
            'booking' => $booking,
            'status' => $status,
            'status_message' => $statusMessage,
            'service' => [
                'name' => $booking->service->name ?? 'Unknown',
                'category' => $booking->service->category->name ?? 'N/A',
                'is_active' => $booking->service->is_active ?? false,
            ],
            'time_info' => [
                'date' => $booking->booking_date->format('F j, Y'),
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'duration_hours' => $booking->duration_hours,
            ],
            'customer' => [
                'name' => $booking->guest->name ?? 'Unknown',
                'email' => $booking->guest->email ?? 'N/A',
            ],
            'payment_info' => [
                'total_price' => $booking->total_price,
                'payment_status' => $booking->payment_status,
            ],
        ];
    }

    /**
     * Get statistics for beach staff dashboard
     *
     * @param int $staffId
     * @param string|null $date
     * @return array
     */
    public function getStaffStats(int $staffId, ?string $date = null): array
    {
        $date = $date ? \Carbon\Carbon::parse($date) : now();

        // Get service assigned to this staff
        $service = \App\Models\BeachService::where('assigned_staff_id', $staffId)
            ->first();

        if (!$service) {
            return [
                'date' => $date->format('F j, Y'),
                'bookings_validated' => 0,
                'total_revenue' => 0,
                'confirmed_bookings' => 0,
                'cancelled_bookings' => 0,
            ];
        }

        // Get bookings validated by this staff today
        $validatedToday = BeachServiceBooking::where('beach_service_id', $service->id)
            ->where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->count();

        // Get revenue from validated bookings today
        $revenueToday = BeachServiceBooking::where('beach_service_id', $service->id)
            ->where('redeemed_by_staff_id', $staffId)
            ->whereDate('redeemed_at', $date)
            ->sum('total_price');

        // Get confirmed bookings for today
        $confirmedToday = BeachServiceBooking::where('beach_service_id', $service->id)
            ->where('booking_date', $date->toDateString())
            ->where('status', 'confirmed')
            ->count();

        // Get cancelled bookings for today
        $cancelledToday = BeachServiceBooking::where('beach_service_id', $service->id)
            ->where('booking_date', $date->toDateString())
            ->where('status', 'cancelled')
            ->count();

        return [
            'date' => $date->format('F j, Y'),
            'bookings_validated' => $validatedToday,
            'total_revenue' => (float) $revenueToday,
            'confirmed_bookings' => $confirmedToday,
            'cancelled_bookings' => $cancelledToday,
        ];
    }

    /**
     * Get recent validation history for staff
     *
     * @param int $staffId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentValidations(int $staffId, int $limit = 20)
    {
        // Get service assigned to this staff
        $service = \App\Models\BeachService::where('assigned_staff_id', $staffId)
            ->first();

        if (!$service) {
            return collect();
        }

        return BeachServiceBooking::where('beach_service_id', $service->id)
            ->where('redeemed_by_staff_id', $staffId)
            ->with(['service', 'guest'])
            ->orderBy('redeemed_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
