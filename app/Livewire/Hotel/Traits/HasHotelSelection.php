<?php

namespace App\Livewire\Hotel\Traits;

use App\Models\Hotel;

trait HasHotelSelection
{
    public $selectedHotelId;
    public $hotel;

    /**
     * Initialize hotel selection
     * Call this in your mount() method
     */
    public function initializeHotelSelection()
    {
        $staffId = auth('staff')->id();

        // Get all hotels assigned to this staff member
        $assignedHotels = Hotel::where('manager_id', $staffId)->get();

        if ($assignedHotels->isEmpty()) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Check if there's a selected hotel in session
        $sessionHotelId = session('hotel_selected_hotel_id');

        // Validate that the session hotel is still assigned to this staff
        if ($sessionHotelId && $assignedHotels->contains('id', $sessionHotelId)) {
            $this->selectedHotelId = $sessionHotelId;
        } else {
            // Default to first assigned hotel
            $this->selectedHotelId = $assignedHotels->first()->id;
            session(['hotel_selected_hotel_id' => $this->selectedHotelId]);
        }

        $this->hotel = Hotel::find($this->selectedHotelId);
    }

    /**
     * Select a different hotel
     */
    public function selectHotel($hotelId)
    {
        $staffId = auth('staff')->id();

        // Verify this hotel is assigned to this staff member
        $hotel = Hotel::where('id', $hotelId)
            ->where('manager_id', $staffId)
            ->first();

        if ($hotel) {
            $this->selectedHotelId = $hotelId;
            $this->hotel = $hotel;
            session(['hotel_selected_hotel_id' => $hotelId]);

            // Call hook if it exists
            if (method_exists($this, 'onHotelChanged')) {
                $this->onHotelChanged();
            }

            session()->flash('success', 'Switched to ' . $hotel->name);
        }
    }

    /**
     * Get assigned hotels for dropdown
     * Call this in your render() method
     */
    public function getAssignedHotelsProperty()
    {
        $staffId = auth('staff')->id();
        return Hotel::where('manager_id', $staffId)->get();
    }
}
