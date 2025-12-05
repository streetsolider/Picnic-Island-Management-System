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
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if (!$staffZone || $schedule->activity->theme_park_zone_id !== $staffZone->id) {
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
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if (!$staffZone || $activity->theme_park_zone_id !== $staffZone->id) {
                session()->flash('error', 'You can only create schedules for activities in your assigned zone.');
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
                $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
                if (!$staffZone || $schedule->activity->theme_park_zone_id !== $staffZone->id) {
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
                $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
                if (!$staffZone || $schedule->activity->theme_park_zone_id !== $staffZone->id) {
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

    public function render()
    {
        // Get scheduled show activities based on role
        if ($this->isManager) {
            $activities = ThemeParkActivity::where('activity_type', 'scheduled')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            // Staff only sees scheduled shows in their assigned zone
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            $activities = $staffZone
                ? ThemeParkActivity::where('activity_type', 'scheduled')
                    ->where('theme_park_zone_id', $staffZone->id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get()
                : collect([]);
        }

        // Get show schedules
        $query = ThemeParkShowSchedule::with(['activity']);

        // Filter by staff zone
        if (!$this->isManager) {
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();
            if ($staffZone) {
                $query->whereHas('activity', function ($q) use ($staffZone) {
                    $q->where('theme_park_zone_id', $staffZone->id);
                });
            } else {
                $query->whereRaw('1 = 0'); // Show nothing if no zone assigned
            }
        }

        // Filter by selected activity
        if ($this->selectedActivity) {
            $query->where('activity_id', $this->selectedActivity);
        }

        $schedules = $query->orderBy('show_date', 'desc')
            ->orderBy('show_time', 'asc')
            ->paginate(15);

        return view('livewire.theme-park.schedules', [
            'schedules' => $schedules,
            'activities' => $activities,
        ]);
    }
}
