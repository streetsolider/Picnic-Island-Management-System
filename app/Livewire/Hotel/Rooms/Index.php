<?php

namespace App\Livewire\Hotel\Rooms;

use App\Livewire\Hotel\Traits\HasHotelSelection;
use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Hotel;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, HasHotelSelection;
    public $currentRoomCount = 0;
    public $remainingCapacity = 0;

    // Filters
    public $search = '';
    public $filterRoomType = '';
    public $filterBedSize = '';
    public $filterAvailability = '';

    // Room creation properties
    public $showCreateModal = false;
    public $room_number = '';
    public $room_type = 'Standard';
    public $bed_size = 'Queen';
    public $bed_count = 'Single';
    public $view = '';
    public $max_occupancy = 2;
    public $selectedAmenities = [];

    // Room editing properties
    public $editingRoomId = null;
    public $editingRoom = null;

    // Room deletion properties
    public $deletingRoomId = null;
    public $deletingRoom = null;

    // Available options
    public $roomTypes = ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'];
    public $bedSizes = ['King', 'Queen', 'Twin'];
    public $bedCounts = ['Single', 'Double', 'Triple', 'Quad'];
    public $views = ['Garden', 'Beach'];

    public function mount()
    {
        $this->initializeHotelSelection();
        $this->updateCapacity();
    }

    public function onHotelChanged()
    {
        $this->updateCapacity();
        $this->resetPage();
    }

    public function updateCapacity()
    {
        $this->currentRoomCount = $this->hotel->rooms()->count();
        $this->remainingCapacity = $this->hotel->room_capacity - $this->currentRoomCount;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRoomType()
    {
        $this->resetPage();
    }

    public function updatingFilterBedSize()
    {
        $this->resetPage();
    }

    public function updatingFilterAvailability()
    {
        $this->resetPage();
    }

    public function openDeleteModal($roomId)
    {
        $this->deletingRoom = Room::where('hotel_id', $this->hotel->id)->findOrFail($roomId);
        $this->deletingRoomId = $this->deletingRoom->id;

        $this->dispatch('open-modal', 'delete-room');
    }

    public function cancelDelete()
    {
        $this->deletingRoomId = null;
        $this->deletingRoom = null;
        $this->dispatch('close-modal', 'delete-room');
    }

    public function confirmDelete()
    {
        if (!$this->deletingRoomId) {
            session()->flash('error', 'No room selected for deletion.');
            return;
        }

        $room = Room::where('hotel_id', $this->hotel->id)->findOrFail($this->deletingRoomId);
        $roomNumber = $room->room_number;
        $room->delete();

        $this->deletingRoomId = null;
        $this->deletingRoom = null;
        $this->updateCapacity();
        $this->dispatch('close-modal', 'delete-room');
        session()->flash('success', "Room {$roomNumber} deleted successfully!");
    }

    public function toggleAvailability($roomId)
    {
        $room = Room::where('hotel_id', $this->hotel->id)->findOrFail($roomId);
        $room->update(['is_available' => !$room->is_available]);

        session()->flash('success', 'Room availability updated!');
    }

    public function openCreateModal()
    {
        // Check capacity before opening modal
        $this->updateCapacity();

        if ($this->remainingCapacity <= 0) {
            session()->flash('error', 'Hotel has reached maximum room capacity. Cannot add more rooms.');
            return;
        }

        $this->resetCreateForm();
        $this->dispatch('open-modal', 'create-room');
    }

    public function resetCreateForm()
    {
        $this->room_number = '';
        $this->room_type = 'Standard';
        $this->bed_size = 'Queen';
        $this->bed_count = 'Single';
        $this->view = '';
        $this->max_occupancy = 2;
        $this->selectedAmenities = [];
        $this->resetValidation();
    }

    protected function roomValidationRules()
    {
        $rules = [
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view' => 'nullable|in:' . implode(',', $this->views),
            'max_occupancy' => 'required|integer|min:1|max:10',
        ];

        // For editing, exclude current room from unique validation
        if ($this->editingRoomId) {
            $rules['room_number'] = 'required|string|max:255|unique:rooms,room_number,' . $this->editingRoomId;
        }

        return $rules;
    }

    public function createRoom()
    {
        // Check capacity before creating
        $this->updateCapacity();
        if ($this->remainingCapacity <= 0) {
            session()->flash('error', 'Hotel has reached maximum room capacity. Cannot add more rooms.');
            $this->dispatch('close-modal', 'create-room');
            return;
        }

        $this->validate($this->roomValidationRules());

        $room = Room::create([
            'hotel_id' => $this->hotel->id,
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'max_occupancy' => $this->max_occupancy,
        ]);

        // Sync amenities
        if (!empty($this->selectedAmenities)) {
            $room->amenities()->sync($this->selectedAmenities);
        }

        $this->updateCapacity();
        $this->resetCreateForm();
        $this->dispatch('close-modal', 'create-room');
        session()->flash('success', 'Room created successfully!');
    }

    public function openEditModal($roomId)
    {
        $this->editingRoom = Room::where('hotel_id', $this->hotel->id)->findOrFail($roomId);
        $this->editingRoomId = $this->editingRoom->id;

        // Populate form fields
        $this->room_number = $this->editingRoom->room_number;
        $this->room_type = $this->editingRoom->room_type;
        $this->bed_size = $this->editingRoom->bed_size;
        $this->bed_count = $this->editingRoom->bed_count;
        $this->view = $this->editingRoom->view ?? '';
        $this->max_occupancy = $this->editingRoom->max_occupancy;
        $this->selectedAmenities = $this->editingRoom->amenities->pluck('id')->toArray();

        $this->dispatch('open-modal', 'edit-room');
    }

    public function resetEditForm()
    {
        $this->editingRoomId = null;
        $this->editingRoom = null;
        $this->room_number = '';
        $this->room_type = 'Standard';
        $this->bed_size = 'Queen';
        $this->bed_count = 'Single';
        $this->view = '';
        $this->max_occupancy = 2;
        $this->selectedAmenities = [];
        $this->resetValidation();
    }

    public function updateRoom()
    {
        if (!$this->editingRoomId) {
            session()->flash('error', 'No room selected for editing.');
            return;
        }

        $this->validate($this->roomValidationRules());

        $room = Room::where('hotel_id', $this->hotel->id)->findOrFail($this->editingRoomId);
        $room->update([
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'max_occupancy' => $this->max_occupancy,
        ]);

        // Sync amenities
        $room->amenities()->sync($this->selectedAmenities ?? []);

        $this->resetEditForm();
        $this->dispatch('close-modal', 'edit-room');
        session()->flash('success', 'Room updated successfully!');
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $rooms = Room::where('hotel_id', $this->hotel->id)
            ->with(['amenities'])
            ->when($this->search, function ($query) {
                $query->where('room_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterRoomType, function ($query) {
                $query->where('room_type', $this->filterRoomType);
            })
            ->when($this->filterBedSize, function ($query) {
                $query->where('bed_size', $this->filterBedSize);
            })
            ->when($this->filterAvailability !== '', function ($query) {
                $query->where('is_available', $this->filterAvailability);
            })
            ->orderBy('room_number')
            ->paginate(10);

        // Load amenities grouped by category for the checklist
        $amenityCategories = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->with(['amenities' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get base prices for each room type
        $basePrices = \App\Models\RoomTypePricing::where('hotel_id', $this->hotel->id)
            ->pluck('base_price', 'room_type')
            ->toArray();

        return view('livewire.hotel.rooms.index', [
            'assignedHotels' => $this->assignedHotels,
            'rooms' => $rooms,
            'roomTypes' => ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'],
            'bedSizes' => ['King', 'Queen', 'Twin'],
            'amenityCategories' => $amenityCategories,
            'basePrices' => $basePrices,
        ]);
    }
}
