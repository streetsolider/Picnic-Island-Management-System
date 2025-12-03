<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\HotelBooking;
use App\Models\Ferry\FerryTicket;
use App\Services\BookingService;
use App\Services\FerryTicketService;
use Livewire\Component;

class MyBookings extends Component
{
    public $bookingType = 'hotel'; // hotel, ferry
    public $activeTab = 'upcoming'; // upcoming, past, cancelled

    public function cancelBooking($bookingId)
    {
        try {
            $booking = HotelBooking::findOrFail($bookingId);

            // Ensure user owns this booking
            if ($booking->guest_id !== auth()->id()) {
                session()->flash('error', 'Unauthorized action.');
                return;
            }

            // Check if booking can be cancelled
            if ($booking->status !== 'confirmed') {
                session()->flash('error', 'Only confirmed bookings can be cancelled.');
                return;
            }

            if (!$booking->check_in_date->isFuture()) {
                session()->flash('error', 'Cannot cancel a booking that has already started or passed.');
                return;
            }

            // Cancel the booking
            $bookingService = app(BookingService::class);
            $bookingService->cancelBooking($booking, 'Cancelled by guest');

            session()->flash('success', 'Booking cancelled successfully. Your refund will be processed within 5-7 business days.');

            // Switch to cancelled tab to show the cancelled booking
            $this->activeTab = 'cancelled';

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    public function cancelFerryTicket($ticketId)
    {
        try {
            $ticket = FerryTicket::findOrFail($ticketId);

            // Ensure user owns this ticket
            if ($ticket->guest_id !== auth()->id()) {
                session()->flash('error', 'Unauthorized action.');
                return;
            }

            // Cancel the ticket
            $ferryService = app(FerryTicketService::class);
            $ferryService->cancelTicket($ticket, 'Cancelled by guest');

            session()->flash('success', 'Ferry ticket cancelled successfully.');

            // Switch to cancelled tab
            $this->activeTab = 'cancelled';

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel ticket: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->bookingType === 'hotel') {
            $query = HotelBooking::where('guest_id', auth()->id())
                ->with(['hotel', 'room'])
                ->orderBy('check_in_date', 'desc');

            $bookings = match($this->activeTab) {
                'upcoming' => (clone $query)->upcoming()->get(),
                'past' => (clone $query)->past()->get(),
                'cancelled' => (clone $query)->where('status', 'cancelled')->get(),
                default => $query->get(),
            };
        } else {
            // Ferry tickets
            $query = FerryTicket::where('guest_id', auth()->id())
                ->with(['schedule.route', 'schedule.vessel', 'hotelBooking'])
                ->orderBy('travel_date', 'desc');

            $bookings = match($this->activeTab) {
                'upcoming' => (clone $query)->where('travel_date', '>=', now()->toDateString())->whereIn('status', ['confirmed', 'pending'])->get(),
                'past' => (clone $query)->where('status', '!=', 'cancelled')->where(function($q) {
                    $q->where('travel_date', '<', now()->toDateString())
                      ->orWhere('status', 'used');
                })->get(),
                'cancelled' => (clone $query)->where('status', 'cancelled')->get(),
                default => $query->get(),
            };
        }

        return view('livewire.visitor.booking.my-bookings', [
            'bookings' => $bookings
        ])->layout('layouts.visitor');
    }
}
