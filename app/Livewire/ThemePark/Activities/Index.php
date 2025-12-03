<?php

namespace App\Livewire\ThemePark\Activities;

use App\Enums\StaffRole;
use App\Models\Staff;
use App\Models\ThemeParkZone;
use App\Models\ThemeParkActivity;
use App\Services\ThemeParkActivityService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Layout('layouts.staff')]
#[Title('Manage Activities')]
class Index extends Component
{
    use WithPagination;

    public $isManager = false;
    public $selectedZoneFilter = ''; // For filtering activities by zone
    public $editMode = false;
    public $activityId;

    #[Validate('required|exists:theme_park_zones,id')]
    public $theme_park_zone_id = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|integer|min:1')]
    public $ticket_cost = 1;

    #[Validate('required|integer|min:1')]
    public $capacity_per_session = 50;

    #[Validate('required|integer|min:5')]
    public $duration_minutes = 30;

    #[Validate('nullable|integer|min:0')]
    public $min_age = null;

    #[Validate('nullable|integer|min:0')]
    public $max_age = null;

    #[Validate('nullable|integer|min:0')]
    public $height_requirement_cm = null;

    #[Validate('nullable|exists:staff,id')]
    public $assigned_staff_id = null;

    public function mount()
    {
        // Check if user is a manager
        $this->isManager = auth('staff')->user()->role->value === 'theme_park_manager';
    }

    public function openForm()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'activity-form');
    }

    public function edit($activityId)
    {
        $activity = ThemeParkActivity::with('assignedStaff')->find($activityId);

        if (!$activity) {
            session()->flash('error', 'Activity not found.');
            return;
        }

        // Staff can only edit their assigned activities
        if (!$this->isManager && $activity->assigned_staff_id !== auth('staff')->id()) {
            session()->flash('error', 'Unauthorized to edit this activity.');
            return;
        }

        $this->activityId = $activity->id;
        $this->theme_park_zone_id = $activity->theme_park_zone_id;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->ticket_cost = $activity->ticket_cost;
        $this->capacity_per_session = $activity->capacity_per_session;
        $this->duration_minutes = $activity->duration_minutes;
        $this->min_age = $activity->min_age;
        $this->max_age = $activity->max_age;
        $this->height_requirement_cm = $activity->height_requirement_cm;
        $this->assigned_staff_id = $activity->assigned_staff_id;

        $this->editMode = true;
        $this->dispatch('open-modal', 'activity-form');
    }

    public function save()
    {
        $this->validate();

        $service = app(ThemeParkActivityService::class);

        $data = [
            'theme_park_zone_id' => $this->theme_park_zone_id,
            'assigned_staff_id' => $this->assigned_staff_id,
            'name' => $this->name,
            'description' => $this->description,
            'ticket_cost' => $this->ticket_cost,
            'capacity_per_session' => $this->capacity_per_session,
            'duration_minutes' => $this->duration_minutes,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'height_requirement_cm' => $this->height_requirement_cm,
            'is_active' => true,
        ];

        // Managers bypass authorization checks (pass null), staff pass their ID for verification
        $staffIdForAuth = $this->isManager ? null : auth('staff')->id();

        if ($this->editMode) {
            $result = $service->updateActivity($this->activityId, $data, $staffIdForAuth);
        } else {
            $result = $service->createActivity($data, $staffIdForAuth);
        }

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->resetForm();
            $this->dispatch('close-modal', 'activity-form');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function toggleActive($activityId)
    {
        $service = app(ThemeParkActivityService::class);
        $staffIdForAuth = $this->isManager ? null : auth('staff')->id();
        $result = $service->toggleActive($activityId, $staffIdForAuth);

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function confirmDelete($activityId)
    {
        $this->activityId = $activityId;
        $this->dispatch('confirm-delete');
    }

    public function delete()
    {
        $service = app(ThemeParkActivityService::class);
        $staffIdForAuth = $this->isManager ? null : auth('staff')->id();
        $result = $service->deleteActivity($this->activityId, $staffIdForAuth);

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }

        $this->activityId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'activityId',
            'theme_park_zone_id',
            'assigned_staff_id',
            'name',
            'description',
            'ticket_cost',
            'capacity_per_session',
            'duration_minutes',
            'min_age',
            'max_age',
            'height_requirement_cm',
            'editMode',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        // Manager sees all activities, Staff sees only their assigned activities
        $query = ThemeParkActivity::with(['zone', 'assignedStaff']);

        if ($this->isManager) {
            // Manager can filter by zone
            if ($this->selectedZoneFilter) {
                $query->where('theme_park_zone_id', $this->selectedZoneFilter);
            }
        } else {
            // Staff only sees activities assigned to them
            $query->where('assigned_staff_id', auth('staff')->id());
        }

        $activities = $query->orderBy('name')->paginate(10);

        // Get zones for dropdowns (manager only)
        $zones = $this->isManager
            ? ThemeParkZone::where('is_active', true)->orderBy('name')->get()
            : collect([]);

        // Get available staff for assignment (manager only)
        $availableStaff = $this->isManager
            ? Staff::where('role', StaffRole::THEME_PARK_STAFF)
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
            : collect([]);

        return view('livewire.theme-park.activities.index', [
            'activities' => $activities,
            'zones' => $zones,
            'availableStaff' => $availableStaff,
        ]);
    }
}
