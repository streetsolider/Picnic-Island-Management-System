<?php

namespace App\Livewire\Admin\Hotels;

use App\Enums\StaffRole;
use App\Models\Hotel;
use App\Models\Staff;
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
    public $hotelId;
    public $name;
    public $location;
    public $description;
    public $star_rating = 3;
    public $policies;
    public $manager_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'star_rating' => 'required|integer|min:1|max:5',
            'policies' => 'nullable|string',
            'manager_id' => 'nullable|exists:staff,id',
            'is_active' => 'boolean',
        ];

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

    public function openEditModal($hotelId)
    {
        $this->resetForm();
        $hotel = Hotel::findOrFail($hotelId);

        $this->hotelId = $hotel->id;
        $this->name = $hotel->name;
        $this->location = $hotel->location;
        $this->description = $hotel->description;
        $this->star_rating = $hotel->star_rating;
        $this->policies = $hotel->policies;
        $this->manager_id = $hotel->manager_id;
        $this->is_active = $hotel->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($hotelId)
    {
        $this->hotelId = $hotelId;
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
        $this->hotelId = null;
        $this->name = '';
        $this->location = '';
        $this->description = '';
        $this->star_rating = 3;
        $this->policies = '';
        $this->manager_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createHotel()
    {
        $this->validate();

        Hotel::create([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'star_rating' => $this->star_rating,
            'policies' => $this->policies,
            'manager_id' => $this->manager_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Hotel created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateHotel()
    {
        $this->validate();

        $hotel = Hotel::findOrFail($this->hotelId);

        $hotel->update([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'star_rating' => $this->star_rating,
            'policies' => $this->policies,
            'manager_id' => $this->manager_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Hotel updated successfully.');
        $this->closeModals();
    }

    public function deleteHotel()
    {
        $hotel = Hotel::findOrFail($this->hotelId);
        $hotel->delete();

        session()->flash('message', 'Hotel deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $hotel->update(['is_active' => !$hotel->is_active]);

        $status = $hotel->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Hotel {$status} successfully.");
    }

    public function render()
    {
        $hotels = Hotel::query()
            ->with('manager')
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

        // Get all hotel managers for the dropdown
        $hotelManagers = Staff::where('role', StaffRole::HOTEL_MANAGER)
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.hotels.index', [
            'hotels' => $hotels,
            'hotelManagers' => $hotelManagers,
        ])->layout('layouts.app');
    }
}
