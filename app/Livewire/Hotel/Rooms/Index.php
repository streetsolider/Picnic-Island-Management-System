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
    public $search = '';
    public $filterRoomType = '';
    public $filterBedSize = '';
    public $filterAvailability = '';

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }
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

    #[Layout('layouts.hotel')]
    public function render()
    {
        $rooms = Room::where('hotel_id', $this->hotel->id)
            ->with(['view', 'amenities'])
            ->when($this->search, function ($query) {
                $query->where('room_number', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
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
