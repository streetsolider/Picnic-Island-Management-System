<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\HotelBooking;
use App\Services\BookingService;
use Livewire\Component;

class MyBookings extends Component
{
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
            $bookingService->cancelBooking($booking->id, 'Cancelled by guest');

            session()->flash('success', 'Booking cancelled successfully. Your refund will be processed within 5-7 business days.');

            // Switch to cancelled tab to show the cancelled booking
            $this->activeTab = 'cancelled';

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = HotelBooking::where('guest_id', auth()->id())
            ->with(['hotel', 'room'])
            ->orderBy('check_in_date', 'desc');

        $bookings = match($this->activeTab) {
            'upcoming' => (clone $query)->upcoming()->get(),
            'past' => (clone $query)->past()->get(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->get(),
            default => $query->get(),
        };

        return view('livewire.visitor.booking.my-bookings', [
            'bookings' => $bookings
        ])->layout('layouts.visitor');
    }
}
