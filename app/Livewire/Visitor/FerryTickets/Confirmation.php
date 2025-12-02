<?php

namespace App\Livewire\Visitor\FerryTickets;

use App\Models\Ferry\FerryTicket;
use Livewire\Component;

class Confirmation extends Component
{
    public FerryTicket $ticket;

    public function mount(FerryTicket $ticket)
    {
        // Ensure user owns this ticket
        if ($ticket->guest_id !== auth()->id()) {
            abort(403);
        }

        $this->ticket = $ticket->load(['schedule.route', 'schedule.vessel', 'hotelBooking']);
    }

    public function render()
    {
        return view('livewire.visitor.ferry-tickets.confirmation')
            ->layout('layouts.visitor');
    }
}
