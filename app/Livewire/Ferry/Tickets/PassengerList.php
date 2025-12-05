<?php

namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PassengerList extends Component
{
    public $vessels;
    public $selectedVesselId;
    public $selectedSchedule = null;
    public $selectedDate = null;
    public $passengers = [];

    public function mount()
    {
        // Get all vessels assigned to current operator
        $this->vessels = FerryVessel::where('operator_id', auth('staff')->id())->get();

        if ($this->vessels->isEmpty()) {
            abort(403, 'No ferry vessels assigned to you.');
        }

        // Get selected vessel from session or use first
        $this->selectedVesselId = session('selected_vessel_id_passengers', $this->vessels->first()->id);

        $this->selectedDate = now()->format('Y-m-d');
    }

    public function selectVessel($vesselId)
    {
        $this->selectedVesselId = $vesselId;
        session(['selected_vessel_id_passengers' => $vesselId]);

        // Reset selected schedule when vessel changes
        $this->selectedSchedule = null;
        $this->passengers = [];
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
        // Get selected vessel
        $selectedVessel = $this->vessels->firstWhere('id', $this->selectedVesselId);

        // Load schedules fresh on every render to include newly added schedules
        $schedules = FerrySchedule::with('route')
            ->where('ferry_vessel_id', $this->selectedVesselId)
            ->get();

        return view('livewire.ferry.tickets.passenger-list', [
            'schedules' => $schedules,
            'selectedVessel' => $selectedVessel,
        ]);
    }
}
