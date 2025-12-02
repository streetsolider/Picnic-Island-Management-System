<?php

namespace App\Livewire\Ferry;

use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $vessels;
    public $selectedVesselId;
    public $stats = [];

    public function mount()
    {
        // Get all vessels assigned to current operator
        $this->vessels = FerryVessel::where('operator_id', auth('staff')->id())->get();

        if ($this->vessels->isEmpty()) {
            abort(403, 'No ferry vessels assigned to you.');
        }

        // Select first vessel by default or get from session
        $this->selectedVesselId = session('selected_vessel_id', $this->vessels->first()->id);

        $this->loadStats();
    }

    public function selectVessel($vesselId)
    {
        $this->selectedVesselId = $vesselId;
        session(['selected_vessel_id' => $vesselId]);
        $this->loadStats();
    }

    public function loadStats()
    {
        // Get statistics for selected vessel
        $service = app(FerryTicketService::class);
        $this->stats = $service->getOperatorStats($this->selectedVesselId);
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        $selectedVessel = $this->vessels->firstWhere('id', $this->selectedVesselId);

        return view('livewire.ferry.dashboard', [
            'selectedVessel' => $selectedVessel,
        ]);
    }
}
