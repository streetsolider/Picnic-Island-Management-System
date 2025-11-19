<?php

namespace App\Livewire\Admin\Ferry\Routes;

use App\Models\Ferry\FerryRoute;
use App\Models\Staff;
use App\Enums\StaffRole;
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
    public $routeId;
    public $name;
    public $origin;
    public $destination;
    public $duration_minutes;
    public $base_price;
    public $operator_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'operator_id' => 'nullable|exists:staff,id',
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

    public function openEditModal($routeId)
    {
        $this->resetForm();
        $route = FerryRoute::findOrFail($routeId);

        $this->routeId = $route->id;
        $this->name = $route->name;
        $this->origin = $route->origin;
        $this->destination = $route->destination;
        $this->duration_minutes = $route->duration_minutes;
        $this->base_price = $route->base_price;
        $this->operator_id = $route->operator_id;
        $this->is_active = $route->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($routeId)
    {
        $this->routeId = $routeId;
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
        $this->routeId = null;
        $this->name = '';
        $this->origin = '';
        $this->destination = '';
        $this->duration_minutes = '';
        $this->base_price = '';
        $this->operator_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createRoute()
    {
        $this->validate();

        FerryRoute::create([
            'name' => $this->name,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'duration_minutes' => $this->duration_minutes,
            'base_price' => $this->base_price,
            'operator_id' => $this->operator_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry route created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateRoute()
    {
        $this->validate();

        $route = FerryRoute::findOrFail($this->routeId);

        $route->update([
            'name' => $this->name,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'duration_minutes' => $this->duration_minutes,
            'base_price' => $this->base_price,
            'operator_id' => $this->operator_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry route updated successfully.');
        $this->closeModals();
    }

    public function deleteRoute()
    {
        $route = FerryRoute::findOrFail($this->routeId);
        $route->delete();

        session()->flash('message', 'Ferry route deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($routeId)
    {
        $route = FerryRoute::findOrFail($routeId);
        $route->update(['is_active' => !$route->is_active]);

        $status = $route->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Ferry route {$status} successfully.");
    }

    public function render()
    {
        $routes = FerryRoute::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('origin', 'like', '%' . $this->search . '%')
                    ->orWhere('destination', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $ferryOperators = Staff::where('role', StaffRole::FERRY_OPERATOR)
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.ferry.routes.index', [
            'routes' => $routes,
            'ferryOperators' => $ferryOperators,
        ]);
    }
}
