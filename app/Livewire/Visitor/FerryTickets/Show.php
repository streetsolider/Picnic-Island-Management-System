<?php

namespace App\Livewire\Visitor\FerryTickets;

use App\Models\Ferry\FerryTicket;
use App\Services\FerryTicketService;
use Livewire\Component;

class Show extends Component
{
    public FerryTicket $ticket;
    public bool $showCancelModal = false;

    public function mount(FerryTicket $ticket)
    {
        // Ensure user owns this ticket
        if ($ticket->guest_id !== auth()->id()) {
            abort(403);
        }

        $this->ticket = $ticket->load(['schedule.route', 'schedule.vessel', 'hotelBooking', 'guest']);
    }

    public function confirmCancel()
    {
        $this->showCancelModal = true;
    }

    public function cancelTicket()
    {
        if (!$this->ticket->canBeCancelled()) {
            session()->flash('error', 'This ticket cannot be cancelled.');
            $this->showCancelModal = false;
            return;
        }

        $service = app(FerryTicketService::class);

        try {
            $service->cancelTicket($this->ticket, 'Cancelled by guest');
            session()->flash('success', 'Ferry ticket cancelled successfully.');
            $this->ticket->refresh();
            $this->showCancelModal = false;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->showCancelModal = false;
        }
    }

    public function render()
    {
        return view('livewire.visitor.ferry-tickets.show')
            ->layout('layouts.visitor');
    }
}
