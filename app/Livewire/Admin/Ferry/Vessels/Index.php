<?php

namespace App\Livewire\Admin\Ferry\Vessels;

use App\Models\Ferry\FerryVessel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $vesselId;
    public $name;
    public $capacity;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
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

    public function openEditModal($vesselId)
    {
        $this->resetForm();
        $vessel = FerryVessel::findOrFail($vesselId);

        $this->vesselId = $vessel->id;
        $this->name = $vessel->name;
        $this->capacity = $vessel->capacity;
        $this->is_active = $vessel->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($vesselId)
    {
        $this->vesselId = $vesselId;
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
        $this->vesselId = null;
        $this->name = '';
        $this->capacity = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createVessel()
    {
        $this->validate();

        FerryVessel::create([
            'name' => $this->name,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry vessel created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateVessel()
    {
        $this->validate();

        $vessel = FerryVessel::findOrFail($this->vesselId);

        $vessel->update([
            'name' => $this->name,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry vessel updated successfully.');
        $this->closeModals();
    }

    public function deleteVessel()
    {
        $vessel = FerryVessel::findOrFail($this->vesselId);
        $vessel->delete();

        session()->flash('message', 'Ferry vessel deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($vesselId)
    {
        $vessel = FerryVessel::findOrFail($vesselId);
        $vessel->update(['is_active' => !$vessel->is_active]);

        $status = $vessel->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Ferry vessel {$status} successfully.");
    }

    public function render()
    {
        $vessels = FerryVessel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.ferry.vessels.index', [
            'vessels' => $vessels,
        ]);
    }
}
