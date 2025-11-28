<?php

namespace App\Livewire\Hotel\Rooms;

use App\Models\Hotel;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $hotel;
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

    // Available options
    public $roomTypes = ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'];
    public $bedSizes = ['King', 'Queen', 'Twin'];
    public $bedCounts = ['Single', 'Double', 'Triple', 'Quad'];
    public $views = ['Garden', 'Beach'];

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Calculate current room count and remaining capacity
        $this->updateCapacity();
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

    public function deleteRoom($roomId)
    {
        $room = Room::where('hotel_id', $this->hotel->id)->findOrFail($roomId);
        $room->delete();

        session()->flash('success', 'Room deleted successfully!');
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
        $this->resetValidation();
    }

    protected function roomValidationRules()
    {
        return [
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view' => 'nullable|in:' . implode(',', $this->views),
            'max_occupancy' => 'required|integer|min:1|max:10',
        ];
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

        Room::create([
            'hotel_id' => $this->hotel->id,
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'max_occupancy' => $this->max_occupancy,
            'base_price' => 0, // Temporary until pricing system is fully implemented
        ]);

        $this->updateCapacity();
        $this->resetCreateForm();
        $this->dispatch('close-modal', 'create-room');
        session()->flash('success', 'Room created successfully!');
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

        return view('livewire.hotel.rooms.index', [
            'rooms' => $rooms,
            'roomTypes' => ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'],
            'bedSizes' => ['King', 'Queen', 'Twin'],
        ]);
    }
}
