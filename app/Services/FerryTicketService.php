<?php

namespace App\Services;

use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryTicket;
use App\Models\Ferry\FerryRoute;
use App\Models\HotelBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class FerryTicketService
{
    /**
     * Get available schedules for a date/route with capacity check
     *
     * @param string $date
     * @param int|null $routeId
     * @param int $passengers
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableSchedules(string $date, ?int $routeId = null, int $passengers = 1)
    {
        $dayOfWeek = Carbon::parse($date)->format('l'); // Monday, Tuesday, etc.

        $query = FerrySchedule::with(['route', 'vessel'])
            ->whereHas('route', function ($q) {
                $q->where('is_active', true);
            })
            ->whereHas('vessel', function ($q) {
                $q->where('is_active', true);
            })
            ->whereJsonContains('days_of_week', $dayOfWeek);

        if ($routeId) {
            $query->where('ferry_route_id', $routeId);
        }

        $schedules = $query->get();

        // Filter by capacity
        return $schedules->filter(function ($schedule) use ($date, $passengers) {
            return $schedule->hasCapacity($date, $passengers);
        });
    }

    /**
     * CRITICAL: Validate guest has valid hotel booking
     *
     * @param int $guestId
     * @return array ['valid' => bool, 'booking' => HotelBooking|null, 'errors' => []]
     */
    public function validateHotelBooking(int $guestId): array
    {
        $booking = HotelBooking::where('guest_id', $guestId)
            ->where('status', 'confirmed')
            ->where('check_in_date', '>=', now()->toDateString())
            ->orderBy('check_in_date', 'asc')
            ->first();

        if (!$booking) {
            return [
                'valid' => false,
                'booking' => null,
                'errors' => ['No valid hotel booking found. You must have a confirmed hotel booking to purchase ferry tickets.'],
            ];
        }

        return [
            'valid' => true,
            'booking' => $booking,
            'errors' => [],
        ];
    }

    /**
     * Validate ticket purchase requirements
     *
     * @param array $data
     * @return array ['valid' => bool, 'errors' => []]
     */
    public function validateBooking(array $data): array
    {
        $errors = [];

        // Validate travel date
        $travelDate = Carbon::parse($data['travel_date']);
        $today = Carbon::today();

        if ($travelDate->lt($today)) {
            $errors[] = 'Travel date cannot be in the past.';
        }

        // Validate hotel booking
        $hotelBooking = HotelBooking::find($data['hotel_booking_id']);
        if (!$hotelBooking) {
            $errors[] = 'Hotel booking not found.';
        } elseif ($hotelBooking->status !== 'confirmed') {
            $errors[] = 'Hotel booking must be confirmed.';
        } else {
            // CRITICAL: Validate travel date matches check-in or check-out date
            $checkInDate = $hotelBooking->check_in_date->format('Y-m-d');
            $checkOutDate = $hotelBooking->check_out_date->format('Y-m-d');

            if ($data['travel_date'] !== $checkInDate && $data['travel_date'] !== $checkOutDate) {
                $errors[] = "Ferry ticket must be for your hotel check-in date ({$checkInDate}) or check-out date ({$checkOutDate}).";
            }
        }

        // Validate schedule
        $schedule = FerrySchedule::find($data['ferry_schedule_id']);
        if (!$schedule) {
            $errors[] = 'Ferry schedule not found.';
        } else {
            // Validate route is active
            if (!$schedule->route || !$schedule->route->is_active) {
                $errors[] = 'This ferry route is not currently active.';
            }

            // Validate vessel is active
            if (!$schedule->vessel || !$schedule->vessel->is_active) {
                $errors[] = 'This ferry vessel is not currently active.';
            }

            // Validate capacity
            if (!$schedule->hasCapacity($data['travel_date'], $data['number_of_passengers'])) {
                $available = $schedule->getAvailableSeats($data['travel_date']);
                $errors[] = "Insufficient capacity. Only {$available} seats available.";
            }
        }

        // Validate passengers count against room max occupancy
        if ($hotelBooking) {
            $maxOccupancy = $hotelBooking->room->max_occupancy;
            if ($data['number_of_passengers'] > $maxOccupancy) {
                $errors[] = "Number of passengers ({$data['number_of_passengers']}) cannot exceed your room's maximum occupancy ({$maxOccupancy} persons).";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Create a new ferry ticket
     *
     * @param array $data
     * @return FerryTicket
     * @throws Exception
     */
    public function createTicket(array $data): FerryTicket
    {
        // Validate booking
        $validation = $this->validateBooking($data);
        if (!$validation['valid']) {
            throw new Exception('Ticket validation failed: ' . implode(', ', $validation['errors']));
        }

        $schedule = FerrySchedule::with(['route', 'vessel'])->findOrFail($data['ferry_schedule_id']);

        // Ferry service is FREE - no pricing
        $pricePerPassenger = 0;
        $totalPrice = 0;

        // Create ticket in a transaction
        return DB::transaction(function () use ($data, $schedule, $pricePerPassenger, $totalPrice) {
            $ticket = FerryTicket::create([
                'guest_id' => $data['guest_id'],
                'hotel_booking_id' => $data['hotel_booking_id'],
                'ferry_schedule_id' => $schedule->id,
                'ferry_route_id' => $schedule->ferry_route_id,
                'ferry_vessel_id' => $schedule->ferry_vessel_id,
                'travel_date' => $data['travel_date'],
                'number_of_passengers' => $data['number_of_passengers'],
                'price_per_passenger' => $pricePerPassenger,
                'total_price' => $totalPrice,
                'payment_status' => $data['payment_status'] ?? 'paid',
                'payment_method' => $data['payment_method'] ?? 'online',
                'status' => 'confirmed',
            ]);

            return $ticket;
        });
    }

    /**
     * Cancel a ferry ticket
     *
     * @param FerryTicket $ticket
     * @param string|null $reason
     * @return bool
     */
    public function cancelTicket(FerryTicket $ticket, ?string $reason = null): bool
    {
        if (!$ticket->canBeCancelled()) {
            throw new Exception('This ticket cannot be cancelled.');
        }

        return $ticket->cancel($reason);
    }

    /**
     * Validate and mark ticket as used by operator
     *
     * @param string $ticketReference
     * @param int $staffId
     * @return array ['success' => bool, 'ticket' => FerryTicket|null, 'message' => string]
     */
    public function validateTicket(string $ticketReference, int $staffId): array
    {
        $ticket = FerryTicket::with(['guest', 'schedule.route', 'schedule.vessel'])
            ->where('ticket_reference', $ticketReference)
            ->first();

        if (!$ticket) {
            return [
                'success' => false,
                'ticket' => null,
                'message' => 'Ticket not found.',
            ];
        }

        if ($ticket->status === 'used') {
            return [
                'success' => false,
                'ticket' => $ticket,
                'message' => 'This ticket has already been used.',
            ];
        }

        if ($ticket->status === 'cancelled') {
            return [
                'success' => false,
                'ticket' => $ticket,
                'message' => 'This ticket has been cancelled.',
            ];
        }

        if ($ticket->status === 'expired') {
            return [
                'success' => false,
                'ticket' => $ticket,
                'message' => 'This ticket has expired.',
            ];
        }

        if (!$ticket->canBeUsed()) {
            return [
                'success' => false,
                'ticket' => $ticket,
                'message' => 'This ticket is not valid for today.',
            ];
        }

        // Mark as used
        $ticket->markAsUsed($staffId);

        return [
            'success' => true,
            'ticket' => $ticket,
            'message' => 'Ticket validated successfully.',
        ];
    }

    /**
     * Get operator statistics for dashboard
     *
     * @param int $vesselId
     * @return array
     */
    public function getOperatorStats(int $vesselId): array
    {
        $vessel = \App\Models\Ferry\FerryVessel::find($vesselId);

        if (!$vessel) {
            return [
                'today_passengers' => 0,
                'upcoming_trips' => 0,
                'total_tickets_month' => 0,
                'total_revenue_month' => 0,
            ];
        }

        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $todayPassengers = FerryTicket::where('ferry_vessel_id', $vessel->id)
            ->where('travel_date', $today)
            ->where('status', 'confirmed')
            ->sum('number_of_passengers');

        $upcomingTrips = FerrySchedule::where('ferry_vessel_id', $vessel->id)
            ->whereHas('tickets', function ($q) {
                $q->where('travel_date', '>=', now()->toDateString())
                  ->where('status', 'confirmed');
            })
            ->distinct('id')
            ->count();

        $monthTickets = FerryTicket::where('ferry_vessel_id', $vessel->id)
            ->whereBetween('travel_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->count();

        $monthRevenue = FerryTicket::where('ferry_vessel_id', $vessel->id)
            ->whereBetween('travel_date', [$startOfMonth, $endOfMonth])
            ->where('payment_status', 'paid')
            ->sum('total_price');

        return [
            'today_passengers' => $todayPassengers,
            'upcoming_trips' => $upcomingTrips,
            'total_tickets_month' => $monthTickets,
            'total_revenue_month' => $monthRevenue,
        ];
    }

    /**
     * Get passenger list for a schedule/date
     *
     * @param int $scheduleId
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPassengerList(int $scheduleId, string $date)
    {
        return FerryTicket::with(['guest', 'hotelBooking'])
            ->where('ferry_schedule_id', $scheduleId)
            ->where('travel_date', $date)
            ->whereIn('status', ['confirmed', 'used'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
