<?php

namespace App\Livewire\Admin\Ferry\Schedules;

use App\Models\Ferry\FerryRoute;
use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $scheduleId;
    public $ferry_route_id;
    public $ferry_vessel_id;
    public $departure_time;
    public $arrival_time;
    public $days_of_week = [];

    protected $queryString = ['search'];

    public function rules()
    {
        return [
            'ferry_route_id' => 'required|exists:ferry_routes,id',
            'ferry_vessel_id' => 'required|exists:ferry_vessels,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($scheduleId)
    {
        $this->resetForm();
        $schedule = FerrySchedule::findOrFail($scheduleId);

        $this->scheduleId = $schedule->id;
        $this->ferry_route_id = $schedule->ferry_route_id;
        $this->ferry_vessel_id = $schedule->ferry_vessel_id;
        $this->departure_time = $schedule->departure_time->format('H:i');
        $this->arrival_time = $schedule->arrival_time->format('H:i');
        $this->days_of_week = $schedule->days_of_week;

        $this->showEditModal = true;
    }

    public function openDeleteModal($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->showDeleteModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->scheduleId = null;
        $this->ferry_route_id = null;
        $this->ferry_vessel_id = null;
        $this->departure_time = '';
        $this->arrival_time = '';
        $this->days_of_week = [];
        $this->resetValidation();
    }

    public function createSchedule()
    {
        $this->validate();

        FerrySchedule::create([
            'ferry_route_id' => $this->ferry_route_id,
            'ferry_vessel_id' => $this->ferry_vessel_id,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'days_of_week' => $this->days_of_week,
        ]);

        session()->flash('message', 'Ferry schedule created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateSchedule()
    {
        $this->validate();

        $schedule = FerrySchedule::findOrFail($this->scheduleId);

        $schedule->update([
            'ferry_route_id' => $this->ferry_route_id,
            'ferry_vessel_id' => $this->ferry_vessel_id,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'days_of_week' => $this->days_of_week,
        ]);

        session()->flash('message', 'Ferry schedule updated successfully.');
        $this->closeModals();
    }

    public function deleteSchedule()
    {
        $schedule = FerrySchedule::findOrFail($this->scheduleId);
        $schedule->delete();

        session()->flash('message', 'Ferry schedule deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function render()
    {
        $schedules = FerrySchedule::query()
            ->with(['route', 'vessel'])
            ->when($this->search, function ($query) {
                $query->whereHas('route', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('vessel', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $routes = FerryRoute::where('is_active', true)->get();
        $vessels = FerryVessel::where('is_active', true)->get();

        return view('livewire.admin.ferry.schedules.index', [
            'schedules' => $schedules,
            'routes' => $routes,
            'vessels' => $vessels,
        ]);
    }
}
