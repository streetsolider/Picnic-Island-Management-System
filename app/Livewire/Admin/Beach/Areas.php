<?php

namespace App\Livewire\Admin\Beach;

use App\Enums\StaffRole;
use App\Models\BeachArea;
use App\Models\Staff;
use Livewire\Component;
use Livewire\WithPagination;

class Areas extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $areaId;
    public $name;
    public $location;
    public $description;
    public $capacity_limit = 100;
    public $opening_time;
    public $closing_time;
    public $assigned_staff_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity_limit' => 'required|integer|min:0',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'assigned_staff_id' => 'nullable|exists:staff,id',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($areaId)
    {
        $this->resetForm();
        $area = BeachArea::findOrFail($areaId);

        $this->areaId = $area->id;
        $this->name = $area->name;
        $this->location = $area->location;
        $this->description = $area->description;
        $this->capacity_limit = $area->capacity_limit;
        $this->opening_time = $area->opening_time;
        $this->closing_time = $area->closing_time;
        $this->assigned_staff_id = $area->assigned_staff_id;
        $this->is_active = $area->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($areaId)
    {
        $this->areaId = $areaId;
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
        $this->areaId = null;
        $this->name = '';
        $this->location = '';
        $this->description = '';
        $this->capacity_limit = 100;
        $this->opening_time = null;
        $this->closing_time = null;
        $this->assigned_staff_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createArea()
    {
        $this->validate();

        BeachArea::create([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'capacity_limit' => $this->capacity_limit,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'assigned_staff_id' => $this->assigned_staff_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach area created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateArea()
    {
        $this->validate();

        $area = BeachArea::findOrFail($this->areaId);

        $area->update([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'capacity_limit' => $this->capacity_limit,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'assigned_staff_id' => $this->assigned_staff_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach area updated successfully.');
        $this->closeModals();
    }

    public function deleteArea()
    {
        $area = BeachArea::findOrFail($this->areaId);
        $area->delete();

        session()->flash('message', 'Beach area deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($areaId)
    {
        $area = BeachArea::findOrFail($areaId);
        $area->update(['is_active' => !$area->is_active]);

        $status = $area->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Beach area {$status} successfully.");
    }

    public function render()
    {
        $beachAreas = BeachArea::query()
            ->with('assignedStaff')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        // Get all theme park staff for the dropdown (they can manage beaches too)
        $themeParkStaff = Staff::where('role', StaffRole::THEME_PARK_STAFF)
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.beach.areas', [
            'beachAreas' => $beachAreas,
            'themeParkStaff' => $themeParkStaff,
        ])->layout('layouts.app');
    }
}
