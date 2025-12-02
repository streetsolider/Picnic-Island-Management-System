<?php

namespace App\Livewire\Ferry\Routes;

use App\Models\Ferry\FerryRoute;
use App\Models\Ferry\FerryVessel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $vessels;
    public $selectedVesselId;
    public $routes;
    public $showForm = false;
    public $editingRouteId = null;

    // Form fields
    public $origin = '';
    public $destination = '';
    public $is_active = true;

    // Search
    public $search = '';

    protected $rules = [
        'origin' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        // Get all vessels assigned to current operator
        $this->vessels = FerryVessel::where('operator_id', auth('staff')->id())->get();

        if ($this->vessels->isEmpty()) {
            abort(403, 'No ferry vessels assigned to you.');
        }

        // Get selected vessel from session or use first
        $this->selectedVesselId = session('selected_vessel_id', $this->vessels->first()->id);

        $this->loadRoutes();
    }

    public function selectVessel($vesselId)
    {
        $this->selectedVesselId = $vesselId;
        session(['selected_vessel_id' => $vesselId]);
        $this->loadRoutes();
    }

    public function loadRoutes()
    {
        $query = FerryRoute::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('origin', 'like', '%' . $this->search . '%')
                  ->orWhere('destination', 'like', '%' . $this->search . '%');
            });
        }

        $this->routes = $query->orderBy('created_at', 'desc')->get();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'route-form');
    }

    public function closeForm()
    {
        $this->dispatch('close-modal', 'route-form');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingRouteId = null;
        $this->origin = '';
        $this->destination = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function edit($routeId)
    {
        $route = FerryRoute::findOrFail($routeId);

        $this->editingRouteId = $route->id;
        $this->origin = $route->origin;
        $this->destination = $route->destination;
        $this->is_active = $route->is_active;

        $this->dispatch('open-modal', 'route-form');
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingRouteId) {
                // Update existing route
                $route = FerryRoute::findOrFail($this->editingRouteId);
                $route->update([
                    'origin' => $this->origin,
                    'destination' => $this->destination,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('success', 'Route updated successfully.');
            } else {
                // Create new route
                FerryRoute::create([
                    'origin' => $this->origin,
                    'destination' => $this->destination,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('success', 'Route created successfully.');
            }

            $this->closeForm();
            $this->loadRoutes();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteRoute($routeId)
    {
        $route = FerryRoute::findOrFail($routeId);

        // Check if route has schedules
        if ($route->schedules()->count() > 0) {
            session()->flash('error', 'Cannot delete route with existing schedules.');
            return;
        }

        $route->delete();
        session()->flash('success', 'Route deleted successfully.');
        $this->loadRoutes();
    }

    public function updatedSearch()
    {
        $this->loadRoutes();
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        $selectedVessel = $this->vessels->firstWhere('id', $this->selectedVesselId);

        return view('livewire.ferry.routes.index', [
            'selectedVessel' => $selectedVessel,
        ]);
    }
}
