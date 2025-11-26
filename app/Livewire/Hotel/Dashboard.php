<?php

namespace App\Livewire\Hotel;

use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomView;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $hotel;
    public $stats;

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Calculate stats
        $this->stats = [
            'total_rooms' => Room::where('hotel_id', $this->hotel->id)->count(),
            'available_rooms' => Room::where('hotel_id', $this->hotel->id)->where('is_available', true)->count(),
            'total_views' => RoomView::where('hotel_id', $this->hotel->id)->where('is_active', true)->count(),
            'amenity_categories' => AmenityCategory::where('hotel_id', $this->hotel->id)->count(),
            'total_amenities' => Amenity::where('hotel_id', $this->hotel->id)->count(),
        ];
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $recentRooms = Room::where('hotel_id', $this->hotel->id)
            ->with('view')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.hotel.dashboard', [
            'recentRooms' => $recentRooms,
        ]);
    }
}
