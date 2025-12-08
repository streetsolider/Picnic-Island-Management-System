<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkShowSchedule;
use App\Models\ThemeParkZone;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Layout('layouts.staff')]
#[Title('Manage Show Schedules')]
class Schedules extends Component
{
    use WithPagination;

    public $isManager = false;

    // View mode: 'shows' or 'hours'
    public $viewMode = 'shows';

    // Scheduled shows properties
    public $editMode = false;
    public $scheduleId;
    public $selectedActivity = '';

    #[Validate('required|exists:theme_park_activities,id')]
    public $activity_id = '';

    #[Validate('required|date|after_or_equal:today')]
    public $show_date = '';

    #[Validate('required|date_format:H:i')]
    public $show_time = '';

    #[Validate('required|integer|min:1')]
    public $venue_capacity = 50;

    // Operating hours properties (for continuous rides)
    public $editHoursMode = false;
    public $hoursActivityId = '';

    #[Validate('nullable|date_format:H:i')]
    public $hours_opening_time = '';

    #[Validate('nullable|date_format:H:i')]
    public $hours_closing_time = '';

    public function mount()
    {
        // Check if user is a manager
        $this->isManager = auth('staff')->user()->role->value === 'theme_park_manager';

        // Set default date to today
        $this->show_date = now()->format('Y-m-d');
    }

    public function openForm()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'schedule-form');
    }

    public function edit($scheduleId)
    {
        $schedule = ThemeParkShowSchedule::with('activity')->find($scheduleId);

        if (!$schedule) {
            session()->flash('error', 'Show schedule not found.');
            return;
        }

        // Verify staff has access
        if (!$this->isManager) {
            if ($schedule->activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'Unauthorized to edit this schedule.');
                return;
            }
        }

        $this->scheduleId = $schedule->id;
        $this->activity_id = $schedule->activity_id;
        $this->show_date = $schedule->show_date->format('Y-m-d');
        $this->show_time = $schedule->show_time;
        $this->venue_capacity = $schedule->venue_capacity;

        $this->editMode = true;
        $this->dispatch('open-modal', 'schedule-form');
    }

    public function save()
    {
        $this->validate();

        // Verify activity is a scheduled show
        $activity = ThemeParkActivity::find($this->activity_id);

        if (!$activity) {
            session()->flash('error', 'Activity not found.');
            return;
        }

        if (!$activity->isScheduled()) {
            session()->flash('error', 'Only scheduled shows can have schedules. This activity is a continuous ride.');
            return;
        }

        // Verify staff has access
        if (!$this->isManager) {
            if ($activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'You can only create schedules for activities assigned to you.');
                return;
            }
        }

        try {
            if ($this->editMode) {
                $schedule = ThemeParkShowSchedule::findOrFail($this->scheduleId);

                // Don't allow editing if tickets have been sold
                if ($schedule->tickets_sold > 0) {
                    session()->flash('error', 'Cannot edit schedule with existing ticket sales. Cancel the schedule instead.');
                    return;
                }

                $schedule->update([
                    'activity_id' => $this->activity_id,
                    'show_date' => $this->show_date,
                    'show_time' => $this->show_time,
                    'venue_capacity' => $this->venue_capacity,
                ]);

                session()->flash('success', 'Show schedule updated successfully.');
            } else {
                // Check for duplicate schedule
                $exists = ThemeParkShowSchedule::where('activity_id', $this->activity_id)
                    ->where('show_date', $this->show_date)
                    ->where('show_time', $this->show_time)
                    ->exists();

                if ($exists) {
                    session()->flash('error', 'A show is already scheduled for this activity at this date and time.');
                    return;
                }

                ThemeParkShowSchedule::create([
                    'activity_id' => $this->activity_id,
                    'show_date' => $this->show_date,
                    'show_time' => $this->show_time,
                    'venue_capacity' => $this->venue_capacity,
                    'tickets_sold' => 0,
                    'status' => 'scheduled',
                ]);

                session()->flash('success', 'Show schedule created successfully.');
            }

            $this->resetForm();
            $this->dispatch('close-modal', 'schedule-form');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save schedule: ' . $e->getMessage());
        }
    }

    public function confirmCancel($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->dispatch('confirm-cancel');
    }

    public function cancelSchedule()
    {
        try {
            $schedule = ThemeParkShowSchedule::findOrFail($this->scheduleId);

            // Verify staff has access
            if (!$this->isManager) {
                if ($schedule->activity->assigned_staff_id !== auth('staff')->id()) {
                    session()->flash('error', 'Unauthorized to cancel this schedule.');
                    return;
                }
            }

            $schedule->status = 'cancelled';
            $schedule->save();

            session()->flash('success', 'Show schedule cancelled. Guests with tickets will be notified.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel schedule: ' . $e->getMessage());
        }

        $this->scheduleId = null;
    }

    public function confirmDelete($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->dispatch('confirm-delete');
    }

    public function delete()
    {
        try {
            $schedule = ThemeParkShowSchedule::findOrFail($this->scheduleId);

            // Verify staff has access
            if (!$this->isManager) {
                if ($schedule->activity->assigned_staff_id !== auth('staff')->id()) {
                    session()->flash('error', 'Unauthorized to delete this schedule.');
                    return;
                }
            }

            // Check if there are ticket sales
            if ($schedule->tickets_sold > 0) {
                session()->flash('error', 'Cannot delete schedule with existing ticket sales. Cancel the schedule instead.');
                return;
            }

            $schedule->delete();
            session()->flash('success', 'Show schedule deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete schedule: ' . $e->getMessage());
        }

        $this->scheduleId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'scheduleId',
            'activity_id',
            'show_date',
            'show_time',
            'venue_capacity',
            'editMode',
        ]);
        $this->show_date = now()->format('Y-m-d');
        $this->venue_capacity = 50;
        $this->resetValidation();
    }

    // Operating Hours Management Methods

    public function switchView($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    public function openHoursForm()
    {
        $this->resetHoursForm();
        $this->dispatch('open-modal', 'hours-form');
    }

    public function editHours($activityId)
    {
        $activity = ThemeParkActivity::find($activityId);

        if (!$activity || $activity->activity_type !== 'continuous') {
            session()->flash('error', 'Activity not found or not a continuous ride.');
            return;
        }

        // Verify staff has access
        if (!$this->isManager) {
            if ($activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'Unauthorized to edit this activity.');
                return;
            }
        }

        $this->hoursActivityId = $activity->id;
        $this->hours_opening_time = $activity->operating_hours_start ? $activity->operating_hours_start->format('H:i') : '';
        $this->hours_closing_time = $activity->operating_hours_end ? $activity->operating_hours_end->format('H:i') : '';

        $this->editHoursMode = true;
        $this->dispatch('open-modal', 'hours-form');
    }

    public function saveHours()
    {
        $this->validate([
            'hoursActivityId' => 'required|exists:theme_park_activities,id',
            'hours_opening_time' => 'nullable|date_format:H:i',
            'hours_closing_time' => 'nullable|date_format:H:i',
        ]);

        $activity = ThemeParkActivity::find($this->hoursActivityId);

        if (!$activity || $activity->activity_type !== 'continuous') {
            session()->flash('error', 'Activity not found or not a continuous ride.');
            return;
        }

        // Verify staff has access
        if (!$this->isManager) {
            if ($activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'Unauthorized to modify this activity.');
                return;
            }
        }

        // Validate that closing time is after opening time
        if ($this->hours_opening_time && $this->hours_closing_time) {
            if ($this->hours_closing_time <= $this->hours_opening_time) {
                $this->addError('hours_closing_time', 'Closing time must be after opening time.');
                return;
            }
        }

        try {
            $activity->operating_hours_start = $this->hours_opening_time ?: null;
            $activity->operating_hours_end = $this->hours_closing_time ?: null;
            $activity->save();

            session()->flash('success', 'Operating hours updated successfully.');
            $this->resetHoursForm();
            $this->dispatch('close-modal', 'hours-form');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update operating hours: ' . $e->getMessage());
        }
    }

    public function clearHours($activityId)
    {
        $activity = ThemeParkActivity::find($activityId);

        if (!$activity || $activity->activity_type !== 'continuous') {
            session()->flash('error', 'Activity not found or not a continuous ride.');
            return;
        }

        // Verify staff has access
        if (!$this->isManager) {
            if ($activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'Unauthorized to modify this activity.');
                return;
            }
        }

        try {
            $activity->operating_hours_start = null;
            $activity->operating_hours_end = null;
            $activity->save();

            session()->flash('success', 'Operating hours cleared. Activity will use zone hours.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clear operating hours: ' . $e->getMessage());
        }
    }

    public function resetHoursForm()
    {
        $this->reset([
            'hoursActivityId',
            'hours_opening_time',
            'hours_closing_time',
            'editHoursMode',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        // Get scheduled show activities based on role
        if ($this->isManager) {
            $activities = ThemeParkActivity::where('activity_type', 'scheduled')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            // Staff only sees scheduled shows assigned to them
            $activities = ThemeParkActivity::where('activity_type', 'scheduled')
                ->where('assigned_staff_id', auth('staff')->id())
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        // Get show schedules
        $query = ThemeParkShowSchedule::with(['activity']);

        // Filter by staff's assigned activities
        if (!$this->isManager) {
            $staffActivityIds = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
                ->where('activity_type', 'scheduled')
                ->pluck('id');

            if ($staffActivityIds->isNotEmpty()) {
                $query->whereIn('activity_id', $staffActivityIds);
            } else {
                $query->whereRaw('1 = 0'); // Show nothing if no activities assigned
            }
        }

        // Filter by selected activity
        if ($this->selectedActivity) {
            $query->where('activity_id', $this->selectedActivity);
        }

        $schedules = $query->orderBy('show_date', 'desc')
            ->orderBy('show_time', 'asc')
            ->paginate(15);

        // Get continuous ride activities for operating hours management
        if ($this->isManager) {
            $continuousActivities = ThemeParkActivity::where('activity_type', 'continuous')
                ->where('is_active', true)
                ->with('zone')
                ->orderBy('name')
                ->get();
        } else {
            // Staff only sees continuous rides assigned to them
            $continuousActivities = ThemeParkActivity::where('activity_type', 'continuous')
                ->where('assigned_staff_id', auth('staff')->id())
                ->where('is_active', true)
                ->with('zone')
                ->orderBy('name')
                ->get();
        }

        // Check if staff has any assigned activities (scheduled OR continuous)
        $hasAssignedActivities = false;
        if ($this->isManager) {
            $hasAssignedActivities = true; // Managers see everything
        } else {
            $hasAssignedActivities = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
                ->where('is_active', true)
                ->exists();
        }

        return view('livewire.theme-park.schedules', [
            'schedules' => $schedules,
            'activities' => $activities,
            'hasAssignedActivities' => $hasAssignedActivities,
            'continuousActivities' => $continuousActivities,
        ]);
    }
}
