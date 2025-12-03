<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkZone;
use Livewire\Component;
use Livewire\WithPagination;

class Zones extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $zoneId;
    public $name;
    public $zone_type;
    public $description;
    public $is_active = true;

    // Zone types (the type itself is the name)
    public $zoneTypes = [
        'Adventure' => 'Adventure',
        'Water Park' => 'Water Park',
        'Kids Area' => 'Kids Area',
        'Entertainment' => 'Entertainment',
        'Dining' => 'Dining',
        'Thrill Rides' => 'Thrill Rides',
        'Family' => 'Family',
    ];

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'zone_type' => 'required|string|max:255',
            'description' => 'nullable|string',
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

    public function openEditModal($zoneId)
    {
        $this->resetForm();
        $zone = ThemeParkZone::findOrFail($zoneId);

        $this->zoneId = $zone->id;
        $this->name = $zone->name;
        $this->zone_type = $zone->zone_type;
        $this->description = $zone->description;
        $this->is_active = $zone->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($zoneId)
    {
        $this->zoneId = $zoneId;
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
        $this->zoneId = null;
        $this->name = '';
        $this->zone_type = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createZone()
    {
        $this->validate();

        ThemeParkZone::create([
            'name' => $this->name,
            'zone_type' => $this->zone_type,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Theme park zone created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateZone()
    {
        $this->validate();

        $zone = ThemeParkZone::findOrFail($this->zoneId);

        $zone->update([
            'name' => $this->name,
            'zone_type' => $this->zone_type,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Theme park zone updated successfully.');
        $this->closeModals();
    }

    public function deleteZone()
    {
        $zone = ThemeParkZone::findOrFail($this->zoneId);
        $zone->delete();

        session()->flash('message', 'Theme park zone deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($zoneId)
    {
        $zone = ThemeParkZone::findOrFail($zoneId);
        $zone->update(['is_active' => !$zone->is_active]);

        $status = $zone->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Theme park zone {$status} successfully.");
    }

    public function render()
    {
        $zones = ThemeParkZone::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('zone_type', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.theme-park.zones', [
            'zones' => $zones,
        ])->layout('layouts.staff');
    }
}
