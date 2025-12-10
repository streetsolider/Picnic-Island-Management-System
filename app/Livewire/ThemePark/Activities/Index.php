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

    // Staff assignment
    public $showStaffAssignmentModal = false;
    public $activityToAssign = null;
    public $selectedStaffId = '';

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

    #[Validate('nullable|integer|min:5')]
    public $duration_minutes = null;

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

    /**
     * Clear operating hours when activity type changes to scheduled.
     */
    public function updatedActivityType($value)
    {
        if ($value === 'scheduled') {
            $this->operating_hours_start = null;
            $this->operating_hours_end = null;
        }
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

        // Staff can only edit activities assigned to them
        if (!$this->isManager) {
            if ($activity->assigned_staff_id !== auth('staff')->id()) {
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
        // Custom validation: duration is required for scheduled shows
        $rules = [];
        if ($this->activity_type === 'scheduled') {
            $rules['duration_minutes'] = 'required|integer|min:5';
        }

        if (!empty($rules)) {
            $this->validate($rules);
        } else {
            $this->validate();
        }

        // Managers can create activities for any zone
        // Staff can create activities but must assign them to themselves
        if (!$this->isManager) {
            // Staff can only create activities for themselves
            // The activity will be auto-assigned to them after creation
        }

        // Prepare data - scheduled shows don't use operating hours
        $data = [
            'theme_park_zone_id' => $this->theme_park_zone_id,
            'name' => $this->name,
            'description' => $this->description,
            'activity_type' => $this->activity_type,
            'credit_cost' => $this->credit_cost,
            'capacity' => $this->capacity,
            'duration_minutes' => $this->duration_minutes,
            'operating_hours_start' => $this->activity_type === 'continuous' ? $this->operating_hours_start : null,
            'operating_hours_end' => $this->activity_type === 'continuous' ? $this->operating_hours_end : null,
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
                $activity = ThemeParkActivity::create($data);

                // Auto-assign staff to their own activities
                if (!$this->isManager) {
                    $activity->assigned_staff_id = auth('staff')->id();
                    $activity->save();
                }

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
                if ($activity->assigned_staff_id !== auth('staff')->id()) {
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
                if ($activity->assigned_staff_id !== auth('staff')->id()) {
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

    public function openStaffAssignment($activityId)
    {
        $this->activityToAssign = ThemeParkActivity::with('assignedStaff')->find($activityId);

        if (!$this->activityToAssign) {
            session()->flash('error', 'Activity not found.');
            return;
        }

        $this->selectedStaffId = $this->activityToAssign->assigned_staff_id ?? '';
        $this->showStaffAssignmentModal = true;
    }

    public function assignStaff()
    {
        if (!$this->activityToAssign) {
            session()->flash('error', 'No activity selected.');
            return;
        }

        try {
            $activity = ThemeParkActivity::findOrFail($this->activityToAssign->id);

            // Allow null to unassign staff
            $activity->assigned_staff_id = $this->selectedStaffId ?: null;
            $activity->save();

            $message = $this->selectedStaffId
                ? 'Staff assigned successfully.'
                : 'Staff unassigned successfully.';

            session()->flash('success', $message);
            $this->showStaffAssignmentModal = false;
            $this->reset(['activityToAssign', 'selectedStaffId']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to assign staff: ' . $e->getMessage());
        }
    }

    public function closeStaffAssignmentModal()
    {
        $this->showStaffAssignmentModal = false;
        $this->reset(['activityToAssign', 'selectedStaffId']);
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
        // Manager sees all activities, Staff sees only activities assigned to them
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

        // Get zones for dropdowns
        $zones = $this->isManager
            ? ThemeParkZone::where('is_active', true)->orderBy('name')->get()
            : ThemeParkZone::where('is_active', true)->orderBy('name')->get(); // Staff can see all zones when creating activities

        // Get theme park staff for assignment (manager only)
        $themeParkStaff = $this->isManager
            ? \App\Models\Staff::where('role', 'theme_park_staff')->orderBy('name')->get()
            : collect();

        return view('livewire.theme-park.activities.index', [
            'activities' => $activities,
            'zones' => $zones,
            'themeParkStaff' => $themeParkStaff,
        ]);
    }
}
