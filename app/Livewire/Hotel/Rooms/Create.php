<?php

namespace App\Livewire\Hotel\Rooms;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomView;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $hotel;

    // Room properties
    public $room_number = '';
    public $room_type = 'Standard';
    public $bed_size = 'Queen';
    public $bed_count = 'Single';
    public $view_id = '';
    public $base_price = '';
    public $max_occupancy = 2;
    public $floor_number = '';
    public $description = '';

    // Available options
    public $roomTypes = ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'];
    public $bedSizes = ['King', 'Queen', 'Twin'];
    public $bedCounts = ['Single', 'Double', 'Triple', 'Quad'];

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }
    }

    protected function rules()
    {
        return [
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view_id' => 'nullable|exists:room_views,id',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'floor_number' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();

        Room::create([
            'hotel_id' => $this->hotel->id,
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view_id' => $this->view_id ?: null,
            'base_price' => $this->base_price,
            'max_occupancy' => $this->max_occupancy,
            'floor_number' => $this->floor_number ?: null,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Room created successfully!');

        return redirect()->route('hotel.rooms.index');
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $views = RoomView::where('hotel_id', $this->hotel->id)
            ->where('is_active', true)
            ->get();

        return view('livewire.hotel.rooms.create', [
            'views' => $views,
        ]);
    }
}
