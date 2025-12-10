<?php

namespace App\Livewire\Visitor\BeachActivities;

use App\Models\BeachServiceBooking;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.visitor')]
#[Title('Booking Confirmed')]
class Confirmation extends Component
{
    public BeachServiceBooking $booking;

    public function mount(BeachServiceBooking $booking)
    {
        // Ensure user can only view their own booking
        if ($booking->guest_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        $this->booking = $booking->load(['service.category', 'guest', 'hotelBooking']);
    }

    public function render()
    {
        return view('livewire.visitor.beach-activities.confirmation');
    }
}
