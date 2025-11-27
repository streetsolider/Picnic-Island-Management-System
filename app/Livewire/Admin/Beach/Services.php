<?php

namespace App\Livewire\Admin\Beach;

use App\Enums\StaffRole;
use App\Models\BeachService;
use App\Models\Staff;
use Livewire\Component;
use Livewire\WithPagination;

class Services extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $serviceId;
    public $name;
    public $service_type;
    public $description;
    public $assigned_staff_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'service_type' => 'required|string|in:' . implode(',', BeachService::SERVICE_TYPES),
            'description' => 'nullable|string',
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

    public function openEditModal($serviceId)
    {
        $this->resetForm();
        $service = BeachService::findOrFail($serviceId);

        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->service_type = $service->service_type;
        $this->description = $service->description;
        $this->assigned_staff_id = $service->assigned_staff_id;
        $this->is_active = $service->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($serviceId)
    {
        $this->serviceId = $serviceId;
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
        $this->serviceId = null;
        $this->name = '';
        $this->service_type = '';
        $this->description = '';
        $this->assigned_staff_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createService()
    {
        $this->validate();

        BeachService::create([
            'name' => $this->name,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'assigned_staff_id' => $this->assigned_staff_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach service created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateService()
    {
        $this->validate();

        $service = BeachService::findOrFail($this->serviceId);

        $service->update([
            'name' => $this->name,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'assigned_staff_id' => $this->assigned_staff_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach service updated successfully.');
        $this->closeModals();
    }

    public function deleteService()
    {
        $service = BeachService::findOrFail($this->serviceId);
        $service->delete();

        session()->flash('message', 'Beach service deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($serviceId)
    {
        $service = BeachService::findOrFail($serviceId);
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Beach service {$status} successfully.");
    }

    public function render()
    {
        $beachServices = BeachService::query()
            ->with('assignedStaff')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('service_type', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        // Get all beach staff for the dropdown
        $beachStaff = Staff::where('role', StaffRole::BEACH_STAFF)
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.beach.services', [
            'beachServices' => $beachServices,
            'beachStaff' => $beachStaff,
            'serviceTypes' => BeachService::SERVICE_TYPES,
        ])->layout('layouts.admin');
    }
}
