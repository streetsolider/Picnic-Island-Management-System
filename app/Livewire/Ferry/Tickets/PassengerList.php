<?php

namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PassengerList extends Component
{
    public $vessel;
    public $schedules;
    public $selectedSchedule = null;
    public $selectedDate = null;
    public $passengers = [];

    public function mount()
    {
        $this->vessel = FerryVessel::where('operator_id', auth('staff')->id())->first();
        if (!$this->vessel) {
            abort(403, 'No ferry vessel assigned to you.');
        }

        $this->schedules = FerrySchedule::with('route')
            ->where('ferry_vessel_id', $this->vessel->id)
            ->get();

        $this->selectedDate = now()->format('Y-m-d');
    }

    public function loadPassengers()
    {
        $this->validate([
            'selectedSchedule' => 'required|exists:ferry_schedules,id',
            'selectedDate' => 'required|date',
        ]);

        $service = app(FerryTicketService::class);
        $this->passengers = $service->getPassengerList($this->selectedSchedule, $this->selectedDate);
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        return view('livewire.ferry.tickets.passenger-list');
    }
}
