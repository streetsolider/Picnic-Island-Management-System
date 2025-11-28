<?php

namespace App\Livewire\Hotel\Rooms;

use App\Models\AmenityCategory;
use App\Models\Hotel;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $hotel;
    public $currentRoomCount = 0;
    public $remainingCapacity = 0;

    // Room properties
    public $room_number = '';
    public $room_type = 'Standard';
    public $bed_size = 'Queen';
    public $bed_count = 'Single';
    public $view = '';
    public $base_price = '';
    public $max_occupancy = 2;
    public $floor_number = '';

    // Amenities
    public $selectedAmenities = [];

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
        $this->currentRoomCount = $this->hotel->rooms()->count();
        $this->remainingCapacity = $this->hotel->room_capacity - $this->currentRoomCount;
    }

    protected function rules()
    {
        return [
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view' => 'nullable|in:' . implode(',', $this->views),
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'floor_number' => 'nullable|integer|min:1',
        ];
    }

    public function toggleCategory($categoryId)
    {
        // Get all amenity IDs in this category
        $category = AmenityCategory::with('amenities')->find($categoryId);

        if (!$category) {
            return;
        }

        $amenityIds = $category->amenities->pluck('id')->toArray();

        // Check if all amenities in this category are selected
        $allSelected = !empty($amenityIds) && empty(array_diff($amenityIds, $this->selectedAmenities));

        if ($allSelected) {
            // Unselect all amenities in this category
            $this->selectedAmenities = array_diff($this->selectedAmenities, $amenityIds);
        } else {
            // Select all amenities in this category
            $this->selectedAmenities = array_unique(array_merge($this->selectedAmenities, $amenityIds));
        }
    }

    public function isCategorySelected($categoryId)
    {
        $category = AmenityCategory::with('amenities')->find($categoryId);

        if (!$category || $category->amenities->isEmpty()) {
            return false;
        }

        $amenityIds = $category->amenities->pluck('id')->toArray();

        return !empty($amenityIds) && empty(array_diff($amenityIds, $this->selectedAmenities));
    }

    public function save()
    {
        // Check capacity before validation
        $currentCount = $this->hotel->rooms()->count();
        if ($currentCount >= $this->hotel->room_capacity) {
            session()->flash('error', 'Hotel has reached maximum room capacity. Cannot add more rooms.');
            return;
        }

        $this->validate();

        $room = Room::create([
            'hotel_id' => $this->hotel->id,
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'base_price' => $this->base_price,
            'max_occupancy' => $this->max_occupancy,
            'floor_number' => $this->floor_number ?: null,
        ]);

        // Sync amenities to the room
        if (!empty($this->selectedAmenities)) {
            $room->amenities()->sync($this->selectedAmenities);
        }

        session()->flash('success', 'Room created successfully!');

        return redirect()->route('hotel.rooms.index');
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $amenityCategories = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->where('is_active', true)
            ->with(['amenities' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('livewire.hotel.rooms.create', [
            'amenityCategories' => $amenityCategories,
        ]);
    }
}
