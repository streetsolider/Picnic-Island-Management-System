<?php

namespace App\Livewire\Hotel;

use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Manage extends Component
{
    public Hotel $hotel;
    public $stats;

    public function mount(Hotel $hotel)
    {
        // Verify the logged-in manager is assigned to this hotel
        if ($hotel->manager_id !== auth('staff')->user()->id) {
            abort(403, 'You are not authorized to manage this hotel.');
        }

        $this->hotel = $hotel;

        // Calculate stats for this hotel
        $this->stats = [
            'total_rooms' => $hotel->rooms()->count(),
            'available_rooms' => $hotel->rooms()->where('is_available', true)->count(),
            'total_views' => $hotel->roomViews()->where('is_active', true)->count(),
            'amenity_categories' => $hotel->amenityCategories()->count(),
            'total_amenities' => $hotel->amenities()->count(),
        ];
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $recentRooms = $this->hotel->rooms()
            ->with('view')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.hotel.manage', [
            'recentRooms' => $recentRooms,
        ]);
    }
}
