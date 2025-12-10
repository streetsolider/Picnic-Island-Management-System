<?php

namespace App\Livewire\Ferry\Schedules;

use App\Models\Ferry\FerryRoute;
use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $vessels;
    public $selectedVesselId;
    public $routes;
    public $schedules;
    public $showForm = false;
    public $editingScheduleId = null;

    // Form fields
    public $ferry_route_id = '';
    public $departure_time = '';
    public $arrival_time = '';
    public $days_of_week = [];

    // Available days
    public $availableDays = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    protected $rules = [
        'ferry_route_id' => 'required|exists:ferry_routes,id',
        'departure_time' => 'required|date_format:H:i',
        'arrival_time' => 'required|date_format:H:i|after:departure_time',
        'days_of_week' => 'required|array|min:1',
        'days_of_week.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
    ];

    public function mount()
    {
        // Get all vessels assigned to current operator
        $this->vessels = FerryVessel::where('operator_id', auth('staff')->id())->get();

        if ($this->vessels->isEmpty()) {
            abort(403, 'No ferry vessels assigned to you.');
        }

        // Get selected vessel from session or use first
        $this->selectedVesselId = session('selected_vessel_id', $this->vessels->first()->id);

        $this->loadData();
    }

    public function selectVessel($vesselId)
    {
        $this->selectedVesselId = $vesselId;
        session(['selected_vessel_id' => $vesselId]);
        $this->loadData();
    }

    public function loadData()
    {
        // Load routes
        $this->routes = FerryRoute::where('is_active', true)->get();

        // Load schedules for selected vessel
        $this->schedules = FerrySchedule::with(['route', 'vessel'])
            ->where('ferry_vessel_id', $this->selectedVesselId)
            ->orderBy('departure_time', 'asc')
            ->get();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'schedule-form');
    }

    public function closeForm()
    {
        $this->dispatch('close-modal', 'schedule-form');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingScheduleId = null;
        $this->ferry_route_id = '';
        $this->departure_time = '';
        $this->arrival_time = '';
        $this->days_of_week = [];
        $this->resetValidation();
    }

    public function edit($scheduleId)
    {
        $schedule = FerrySchedule::findOrFail($scheduleId);

        $this->editingScheduleId = $schedule->id;
        $this->ferry_route_id = $schedule->ferry_route_id;
        $this->departure_time = $schedule->departure_time->format('H:i');
        $this->arrival_time = $schedule->arrival_time->format('H:i');
        $this->days_of_week = $schedule->days_of_week;

        $this->dispatch('open-modal', 'schedule-form');
    }

    public function save()
    {
        $this->validate();

        if ($this->editingScheduleId) {
            // Update existing schedule
            $schedule = FerrySchedule::findOrFail($this->editingScheduleId);
            $schedule->update([
                'ferry_route_id' => $this->ferry_route_id,
                'departure_time' => $this->departure_time,
                'arrival_time' => $this->arrival_time,
                'days_of_week' => $this->days_of_week,
            ]);

            session()->flash('success', 'Schedule updated successfully.');
        } else {
            // Create new schedule
            FerrySchedule::create([
                'ferry_route_id' => $this->ferry_route_id,
                'ferry_vessel_id' => $this->selectedVesselId,
                'departure_time' => $this->departure_time,
                'arrival_time' => $this->arrival_time,
                'days_of_week' => $this->days_of_week,
            ]);

            session()->flash('success', 'Schedule created successfully.');
        }

        $this->closeForm();
        $this->loadData();
    }

    public function deleteSchedule($scheduleId)
    {
        $schedule = FerrySchedule::findOrFail($scheduleId);

        // Check if schedule has tickets
        if ($schedule->tickets()->count() > 0) {
            session()->flash('error', 'Cannot delete schedule with existing tickets.');
            return;
        }

        $schedule->delete();
        session()->flash('success', 'Schedule deleted successfully.');
        $this->loadData();
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        $selectedVessel = $this->vessels->firstWhere('id', $this->selectedVesselId);

        return view('livewire.ferry.schedules.index', [
            'selectedVessel' => $selectedVessel,
        ]);
    }
}
