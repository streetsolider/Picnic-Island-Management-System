<?php

namespace App\Livewire\ThemePark\Activities;

use App\Models\ThemeParkZone;
use App\Models\ThemeParkActivity;
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

    #[Validate('required|in:continuous,scheduled')]
    public $activity_type = 'continuous';

    #[Validate('required|integer|min:1')]
    public $credit_cost = 1;

    #[Validate('nullable|integer|min:1')]
    public $capacity = null;

    #[Validate('required|integer|min:5')]
    public $duration_minutes = 30;

    #[Validate('nullable|date_format:H:i')]
    public $operating_hours_start = null;

    #[Validate('nullable|date_format:H:i')]
    public $operating_hours_end = null;

    #[Validate('nullable|integer|min:0')]
    public $min_age = null;

    #[Validate('nullable|integer|min:0')]
    public $max_age = null;

    #[Validate('nullable|integer|min:0')]
    public $height_requirement_cm = null;

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
        $activity = ThemeParkActivity::find($activityId);

        if (!$activity) {
            session()->flash('error', 'Activity not found.');
            return;
        }

        // Staff can only edit activities in their assigned zone
        if (!$this->isManager) {
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if (!$staffZone || $activity->theme_park_zone_id !== $staffZone->id) {
                session()->flash('error', 'Unauthorized to edit this activity.');
                return;
            }
        }

        $this->activityId = $activity->id;
        $this->theme_park_zone_id = $activity->theme_park_zone_id;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->activity_type = $activity->activity_type;
        $this->credit_cost = $activity->credit_cost;
        $this->capacity = $activity->capacity;
        $this->duration_minutes = $activity->duration_minutes;
        $this->operating_hours_start = $activity->operating_hours_start?->format('H:i');
        $this->operating_hours_end = $activity->operating_hours_end?->format('H:i');
        $this->min_age = $activity->min_age;
        $this->max_age = $activity->max_age;
        $this->height_requirement_cm = $activity->height_requirement_cm;

        $this->editMode = true;
        $this->dispatch('open-modal', 'activity-form');
    }

    public function save()
    {
        $this->validate();

        // Verify staff has access to this zone
        if (!$this->isManager) {
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if (!$staffZone || $this->theme_park_zone_id != $staffZone->id) {
                session()->flash('error', 'You can only create activities in your assigned zone.');
                return;
            }
        }

        $data = [
            'theme_park_zone_id' => $this->theme_park_zone_id,
            'name' => $this->name,
            'description' => $this->description,
            'activity_type' => $this->activity_type,
            'credit_cost' => $this->credit_cost,
            'capacity' => $this->capacity,
            'duration_minutes' => $this->duration_minutes,
            'operating_hours_start' => $this->operating_hours_start,
            'operating_hours_end' => $this->operating_hours_end,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'height_requirement_cm' => $this->height_requirement_cm,
            'is_active' => true,
        ];

        try {
            if ($this->editMode) {
                $activity = ThemeParkActivity::findOrFail($this->activityId);
                $activity->update($data);
                session()->flash('success', 'Activity updated successfully.');
            } else {
                ThemeParkActivity::create($data);
                session()->flash('success', 'Activity created successfully.');
            }

            $this->resetForm();
            $this->dispatch('close-modal', 'activity-form');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save activity: ' . $e->getMessage());
        }
    }

    public function toggleActive($activityId)
    {
        try {
            $activity = ThemeParkActivity::findOrFail($activityId);

            // Verify staff has access
            if (!$this->isManager) {
                $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
                if (!$staffZone || $activity->theme_park_zone_id !== $staffZone->id) {
                    session()->flash('error', 'Unauthorized to modify this activity.');
                    return;
                }
            }

            $activity->is_active = !$activity->is_active;
            $activity->save();

            session()->flash('success', 'Activity status updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to toggle activity: ' . $e->getMessage());
        }
    }

    public function confirmDelete($activityId)
    {
        $this->activityId = $activityId;
        $this->dispatch('confirm-delete');
    }

    public function delete()
    {
        try {
            $activity = ThemeParkActivity::findOrFail($this->activityId);

            // Verify staff has access
            if (!$this->isManager) {
                $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
                if (!$staffZone || $activity->theme_park_zone_id !== $staffZone->id) {
                    session()->flash('error', 'Unauthorized to delete this activity.');
                    return;
                }
            }

            $activity->delete();
            session()->flash('success', 'Activity deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete activity: ' . $e->getMessage());
        }

        $this->activityId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'activityId',
            'theme_park_zone_id',
            'name',
            'description',
            'activity_type',
            'credit_cost',
            'capacity',
            'duration_minutes',
            'operating_hours_start',
            'operating_hours_end',
            'min_age',
            'max_age',
            'height_requirement_cm',
            'editMode',
        ]);
        $this->activity_type = 'continuous';
        $this->credit_cost = 1;
        $this->resetValidation();
    }

    public function render()
    {
        // Manager sees all activities, Staff sees only activities in their assigned zone
        $query = ThemeParkActivity::with(['zone']);

        if ($this->isManager) {
            // Manager can filter by zone
            if ($this->selectedZoneFilter) {
                $query->where('theme_park_zone_id', $this->selectedZoneFilter);
            }
        } else {
            // Staff only sees activities in their assigned zone
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if ($staffZone) {
                $query->where('theme_park_zone_id', $staffZone->id);
            } else {
                // Staff has no assigned zone, show nothing
                $query->whereRaw('1 = 0');
            }
        }

        $activities = $query->orderBy('name')->paginate(10);

        // Get zones for dropdowns
        $zones = $this->isManager
            ? ThemeParkZone::where('is_active', true)->orderBy('name')->get()
            : ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->get();

        return view('livewire.theme-park.activities.index', [
            'activities' => $activities,
            'zones' => $zones,
        ]);
    }
}
