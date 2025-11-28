<?php

namespace App\Livewire\Hotel\Rooms;

use App\Models\Hotel;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public $hotel;
    public $room;

    // Room properties
    public $room_number = '';
    public $room_type = 'Standard';
    public $bed_size = 'Queen';
    public $bed_count = 'Single';
    public $view = '';
    public $base_price = '';
    public $max_occupancy = 2;
    public $floor_number = '';

    // Available options
    public $roomTypes = ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'];
    public $bedSizes = ['King', 'Queen', 'Twin'];
    public $bedCounts = ['Single', 'Double', 'Triple', 'Quad'];
    public $views = ['Garden', 'Beach'];

    public function mount($id)
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Get the room to edit
        $this->room = Room::where('hotel_id', $this->hotel->id)->findOrFail($id);

        // Populate form fields
        $this->room_number = $this->room->room_number;
        $this->room_type = $this->room->room_type;
        $this->bed_size = $this->room->bed_size;
        $this->bed_count = $this->room->bed_count;
        $this->view = $this->room->view ?? '';
        $this->base_price = $this->room->base_price;
        $this->max_occupancy = $this->room->max_occupancy;
        $this->floor_number = $this->room->floor_number ?? '';
    }

    protected function rules()
    {
        return [
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $this->room->id,
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view' => 'nullable|in:' . implode(',', $this->views),
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'floor_number' => 'nullable|integer|min:1',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->room->update([
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'base_price' => $this->base_price,
            'max_occupancy' => $this->max_occupancy,
            'floor_number' => $this->floor_number ?: null,
        ]);

        session()->flash('success', 'Room updated successfully!');

        return redirect()->route('hotel.rooms.index');
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.rooms.edit');
    }
}
