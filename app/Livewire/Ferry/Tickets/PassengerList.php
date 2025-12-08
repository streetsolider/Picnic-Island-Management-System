<?php

namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryTicket;
use App\Models\Ferry\FerryVessel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PassengerList extends Component
{
    public $vessels;
    public $selectedVesselId;
    public $selectedSchedule = '';
    public $selectedDate;
    public $allPassengers = [];

    public function mount()
    {
        // Get all vessels assigned to current operator
        $this->vessels = FerryVessel::where('operator_id', auth('staff')->id())->get();

        if ($this->vessels->isEmpty()) {
            abort(403, 'No ferry vessels assigned to you.');
        }

        // Get selected vessel from session, default to 'all' if multiple vessels, otherwise first vessel
        if ($this->vessels->count() > 1) {
            $this->selectedVesselId = session('selected_vessel_id_passengers', 'all');
        } else {
            $this->selectedVesselId = $this->vessels->first()->id;
        }

        $this->selectedDate = now()->format('Y-m-d');

        // Auto-load passengers for today
        $this->loadAllPassengers();
    }

    public function updatedSelectedVesselId($value)
    {
        session(['selected_vessel_id_passengers' => $value]);

        // Reset schedule filter and reload passengers
        $this->selectedSchedule = '';
        $this->loadAllPassengers();
    }

    public function updatedSelectedDate()
    {
        // Auto-reload when date changes
        $this->loadAllPassengers();
    }

    /**
     * Load all passengers for selected vessel(s) and date
     */
    public function loadAllPassengers()
    {
        $query = FerryTicket::with(['guest', 'schedule.route', 'vessel', 'hotelBooking']);

        // If "All Vessels" is selected, load from all assigned vessels
        if ($this->selectedVesselId === 'all') {
            $vesselIds = $this->vessels->pluck('id')->toArray();
            $query->whereIn('ferry_vessel_id', $vesselIds);
        } else {
            // Load from specific vessel
            $query->where('ferry_vessel_id', $this->selectedVesselId);
        }

        $this->allPassengers = $query
            ->where('travel_date', $this->selectedDate)
            ->whereIn('status', ['confirmed', 'used'])
            ->orderByRaw("FIELD(status, 'used', 'confirmed')")
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get filtered passengers based on selected schedule
     */
    #[Computed]
    public function filteredPassengers()
    {
        if (empty($this->selectedSchedule)) {
            return $this->allPassengers;
        }

        return $this->allPassengers->filter(function ($passenger) {
            return $passenger->ferry_schedule_id == $this->selectedSchedule;
        });
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        // Get selected vessel (null if "All Vessels")
        $selectedVessel = $this->selectedVesselId === 'all'
            ? null
            : $this->vessels->firstWhere('id', $this->selectedVesselId);

        // Load schedules for selected vessel(s)
        if ($this->selectedVesselId === 'all') {
            $vesselIds = $this->vessels->pluck('id')->toArray();
            $schedules = FerrySchedule::with(['route', 'vessel'])
                ->whereIn('ferry_vessel_id', $vesselIds)
                ->get();
        } else {
            $schedules = FerrySchedule::with('route')
                ->where('ferry_vessel_id', $this->selectedVesselId)
                ->get();
        }

        return view('livewire.ferry.tickets.passenger-list', [
            'schedules' => $schedules,
            'selectedVessel' => $selectedVessel,
        ]);
    }
}
