<?php

namespace App\Livewire\Admin\Beach;

use App\Enums\StaffRole;
use App\Models\BeachActivityCategory;
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
    public $beach_activity_category_id;
    public $name;
    public $service_type;
    public $description;
    public $booking_type = 'fixed_slot';
    public $slot_duration_minutes;
    public $slot_price;
    public $price_per_hour;
    public $capacity_limit;
    public $concurrent_capacity = 1;
    public $opening_time;
    public $closing_time;
    public $assigned_staff_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        $rules = [
            'beach_activity_category_id' => 'required|exists:beach_activity_categories,id',
            'name' => 'required|string|max:255',
            'service_type' => 'required|string|in:' . implode(',', BeachService::SERVICE_TYPES),
            'description' => 'nullable|string',
            'booking_type' => 'required|in:fixed_slot,flexible_duration',
            'capacity_limit' => 'required|integer|min:1',
            'concurrent_capacity' => 'required|integer|min:1',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'assigned_staff_id' => 'nullable|exists:staff,id',
            'is_active' => 'boolean',
        ];

        // Conditional validation based on booking type
        if ($this->booking_type === 'fixed_slot') {
            $rules['slot_duration_minutes'] = 'required|integer|min:15';
            $rules['slot_price'] = 'required|numeric|min:0';
        } else {
            $rules['price_per_hour'] = 'required|numeric|min:0';
        }

        return $rules;
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
        $this->beach_activity_category_id = $service->beach_activity_category_id;
        $this->name = $service->name;
        $this->service_type = $service->service_type;
        $this->description = $service->description;
        $this->booking_type = $service->booking_type;
        $this->slot_duration_minutes = $service->slot_duration_minutes;
        $this->slot_price = $service->slot_price;
        $this->price_per_hour = $service->price_per_hour;
        $this->capacity_limit = $service->capacity_limit;
        $this->concurrent_capacity = $service->concurrent_capacity;
        $this->opening_time = $service->opening_time ? substr($service->opening_time, 0, 5) : null; // Format to HH:MM
        $this->closing_time = $service->closing_time ? substr($service->closing_time, 0, 5) : null;
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
        $this->beach_activity_category_id = null;
        $this->name = '';
        $this->service_type = '';
        $this->description = '';
        $this->booking_type = 'fixed_slot';
        $this->slot_duration_minutes = null;
        $this->slot_price = null;
        $this->price_per_hour = null;
        $this->capacity_limit = null;
        $this->concurrent_capacity = 1;
        $this->opening_time = null;
        $this->closing_time = null;
        $this->assigned_staff_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createService()
    {
        $this->validate();

        BeachService::create([
            'beach_activity_category_id' => $this->beach_activity_category_id,
            'name' => $this->name,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'booking_type' => $this->booking_type,
            'slot_duration_minutes' => $this->slot_duration_minutes,
            'slot_price' => $this->slot_price,
            'price_per_hour' => $this->price_per_hour,
            'capacity_limit' => $this->capacity_limit,
            'concurrent_capacity' => $this->concurrent_capacity,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
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
            'beach_activity_category_id' => $this->beach_activity_category_id,
            'name' => $this->name,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'booking_type' => $this->booking_type,
            'slot_duration_minutes' => $this->slot_duration_minutes,
            'slot_price' => $this->slot_price,
            'price_per_hour' => $this->price_per_hour,
            'capacity_limit' => $this->capacity_limit,
            'concurrent_capacity' => $this->concurrent_capacity,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
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
            ->with(['assignedStaff', 'category'])
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

        // Get all active categories for the dropdown
        $categories = BeachActivityCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.beach.services', [
            'beachServices' => $beachServices,
            'beachStaff' => $beachStaff,
            'categories' => $categories,
            'serviceTypes' => BeachService::SERVICE_TYPES,
        ])->layout('layouts.admin');
    }
}
