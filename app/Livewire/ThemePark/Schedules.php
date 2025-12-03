<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivitySchedule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Layout('layouts.staff')]
#[Title('Manage Schedules')]
class Schedules extends Component
{
    use WithPagination;

    public $editMode = false;
    public $scheduleId;
    public $selectedActivity = '';

    #[Validate('required|exists:theme_park_activities,id')]
    public $activity_id = '';

    #[Validate('required|date|after_or_equal:today')]
    public $schedule_date = '';

    #[Validate('required|date_format:H:i')]
    public $start_time = '';

    #[Validate('required|date_format:H:i|after:start_time')]
    public $end_time = '';

    #[Validate('required|integer|min:1')]
    public $available_slots = 1;

    public function mount()
    {
        // Set default date to today
        $this->schedule_date = now()->format('Y-m-d');
    }

    public function openForm()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'schedule-form');
    }

    public function edit($scheduleId)
    {
        $schedule = ThemeParkActivitySchedule::with('activity')->find($scheduleId);

        if (!$schedule) {
            session()->flash('error', 'Schedule not found.');
            return;
        }

        // Staff can only edit schedules for their assigned activities
        if ($schedule->activity->assigned_staff_id !== auth('staff')->id()) {
            session()->flash('error', 'Unauthorized to edit this schedule.');
            return;
        }

        $this->scheduleId = $schedule->id;
        $this->activity_id = $schedule->activity_id;
        $this->schedule_date = $schedule->schedule_date->format('Y-m-d');
        $this->start_time = $schedule->start_time;
        $this->end_time = $schedule->end_time;
        $this->available_slots = $schedule->available_slots;

        $this->editMode = true;
        $this->dispatch('open-modal', 'schedule-form');
    }

    public function save()
    {
        $this->validate();

        // Verify staff owns this activity
        $activity = ThemeParkActivity::find($this->activity_id);
        if ($activity->assigned_staff_id !== auth('staff')->id()) {
            session()->flash('error', 'You can only create schedules for your assigned activities.');
            return;
        }

        if ($this->editMode) {
            $schedule = ThemeParkActivitySchedule::find($this->scheduleId);

            if (!$schedule || $schedule->activity->assigned_staff_id !== auth('staff')->id()) {
                session()->flash('error', 'Unauthorized.');
                return;
            }

            $schedule->update([
                'activity_id' => $this->activity_id,
                'schedule_date' => $this->schedule_date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'available_slots' => $this->available_slots,
            ]);

            session()->flash('success', 'Schedule updated successfully.');
        } else {
            ThemeParkActivitySchedule::create([
                'activity_id' => $this->activity_id,
                'schedule_date' => $this->schedule_date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'available_slots' => $this->available_slots,
                'booked_slots' => 0,
            ]);

            session()->flash('success', 'Schedule created successfully.');
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'schedule-form');
    }

    public function confirmDelete($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->dispatch('confirm-delete');
    }

    public function delete()
    {
        $schedule = ThemeParkActivitySchedule::find($this->scheduleId);

        if (!$schedule || $schedule->activity->assigned_staff_id !== auth('staff')->id()) {
            session()->flash('error', 'Unauthorized to delete this schedule.');
            return;
        }

        // Check if there are bookings
        if ($schedule->booked_slots > 0) {
            session()->flash('error', 'Cannot delete schedule with existing bookings.');
            return;
        }

        $schedule->delete();

        session()->flash('success', 'Schedule deleted successfully.');
        $this->scheduleId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'scheduleId',
            'activity_id',
            'schedule_date',
            'start_time',
            'end_time',
            'available_slots',
            'editMode',
        ]);
        $this->schedule_date = now()->format('Y-m-d');
        $this->available_slots = 1;
        $this->resetValidation();
    }

    public function render()
    {
        // Get staff's assigned activities
        $myActivities = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get schedules for selected activity or all staff activities
        $query = ThemeParkActivitySchedule::with(['activity'])
            ->whereHas('activity', function ($q) {
                $q->where('assigned_staff_id', auth('staff')->id());
            });

        if ($this->selectedActivity) {
            $query->where('activity_id', $this->selectedActivity);
        }

        $schedules = $query->orderBy('schedule_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('livewire.theme-park.schedules', [
            'schedules' => $schedules,
            'myActivities' => $myActivities,
        ]);
    }
}
