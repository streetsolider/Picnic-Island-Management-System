<?php

namespace App\Livewire\ThemePark\Activities;

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

    public $zone;
    public $showForm = false;
    public $editMode = false;
    public $activityId;

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

    public function mount()
    {
        $this->zone = ThemeParkZone::where('assigned_staff_id', auth()->id())
            ->with(['activities'])
            ->first();

        if (!$this->zone) {
            session()->flash('error', 'No zone assigned to you.');
        }
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($activityId)
    {
        $activity = ThemeParkActivity::find($activityId);

        if (!$activity || $activity->theme_park_zone_id !== $this->zone->id) {
            session()->flash('error', 'Activity not found or unauthorized.');
            return;
        }

        $this->activityId = $activity->id;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->ticket_cost = $activity->ticket_cost;
        $this->capacity_per_session = $activity->capacity_per_session;
        $this->duration_minutes = $activity->duration_minutes;
        $this->min_age = $activity->min_age;
        $this->max_age = $activity->max_age;
        $this->height_requirement_cm = $activity->height_requirement_cm;

        $this->editMode = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $service = app(ThemeParkActivityService::class);

        $data = [
            'theme_park_zone_id' => $this->zone->id,
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

        if ($this->editMode) {
            $result = $service->updateActivity($this->activityId, $data, auth()->id());
        } else {
            $result = $service->createActivity($data, auth()->id());
        }

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->resetForm();
            $this->showForm = false;
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function toggleActive($activityId)
    {
        $service = app(ThemeParkActivityService::class);
        $result = $service->toggleActive($activityId, auth()->id());

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
        $result = $service->deleteActivity($this->activityId, auth()->id());

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
        $activities = $this->zone
            ? ThemeParkActivity::where('theme_park_zone_id', $this->zone->id)
                ->orderBy('name')
                ->paginate(10)
            : collect([]);

        return view('livewire.theme-park.activities.index', [
            'activities' => $activities,
        ]);
    }
}
