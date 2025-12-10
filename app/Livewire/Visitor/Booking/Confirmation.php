<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\HotelBooking;
use Livewire\Component;

class Confirmation extends Component
{
    public HotelBooking $booking;

    public function mount(HotelBooking $booking)
    {
        // Ensure user owns this booking
        if ($booking->guest_id !== auth()->id()) {
            abort(403);
        }

        $this->booking = $booking->load(['hotel', 'room', 'guest']);
    }

    public function render()
    {
        return view('livewire.visitor.booking.confirmation')
            ->layout('layouts.visitor');
    }
}
