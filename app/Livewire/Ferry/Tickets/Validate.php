<?php

namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Validate extends Component
{
    public $vessel;
    public $ticketReference = '';
    public $ticket = null;
    public $validationResult = null;

    public function mount()
    {
        $this->vessel = FerryVessel::where('operator_id', auth('staff')->id())->first();
        if (!$this->vessel) {
            abort(403, 'No ferry vessel assigned to you.');
        }
    }

    public function validateTicket()
    {
        $this->validate([
            'ticketReference' => 'required|string',
        ]);

        $service = app(FerryTicketService::class);
        $this->validationResult = $service->validateTicket(
            $this->ticketReference,
            auth('staff')->id()
        );

        if ($this->validationResult['success']) {
            $this->ticket = $this->validationResult['ticket'];
            session()->flash('success', $this->validationResult['message']);
        } else {
            $this->ticket = $this->validationResult['ticket'];
            session()->flash('error', $this->validationResult['message']);
        }
    }

    public function resetForm()
    {
        $this->ticketReference = '';
        $this->ticket = null;
        $this->validationResult = null;
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        return view('livewire.ferry.tickets.validate');
    }
}
