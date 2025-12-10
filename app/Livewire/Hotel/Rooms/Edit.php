<?php

namespace App\Livewire\Hotel\Rooms;

use App\Models\AmenityCategory;
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
    public $max_occupancy = 2;

    // Amenities
    public $selectedAmenities = [];

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
        $this->max_occupancy = $this->room->max_occupancy;

        // Load existing amenities
        $this->selectedAmenities = $this->room->amenities->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $this->room->id,
            'room_type' => 'required|in:' . implode(',', $this->roomTypes),
            'bed_size' => 'required|in:' . implode(',', $this->bedSizes),
            'bed_count' => 'required|in:' . implode(',', $this->bedCounts),
            'view' => 'nullable|in:' . implode(',', $this->views),
            'max_occupancy' => 'required|integer|min:1|max:10',
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
        $this->validate();

        $this->room->update([
            'room_number' => $this->room_number,
            'room_type' => $this->room_type,
            'bed_size' => $this->bed_size,
            'bed_count' => $this->bed_count,
            'view' => $this->view ?: null,
            'max_occupancy' => $this->max_occupancy,
        ]);

        // Sync amenities to the room
        $this->room->amenities()->sync($this->selectedAmenities);

        session()->flash('success', 'Room updated successfully!');

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

        return view('livewire.hotel.rooms.edit', [
            'amenityCategories' => $amenityCategories,
        ]);
    }
}
