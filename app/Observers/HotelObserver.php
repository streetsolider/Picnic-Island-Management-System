<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Models\Hotel;

class HotelObserver
{
    /**
     * Handle the Hotel "created" event.
     */
    public function created(Hotel $hotel): void
    {
        // Automatically create a hotel gallery when a hotel is created
        Gallery::create([
            'hotel_id' => $hotel->id,
            'name' => $hotel->name . ' Gallery',
            'description' => 'Main gallery for ' . $hotel->name,
            'type' => Gallery::TYPE_HOTEL,
        ]);
    }

    /**
     * Handle the Hotel "updated" event.
     */
    public function updated(Hotel $hotel): void
    {
        //
    }

    /**
     * Handle the Hotel "deleted" event.
     */
    public function deleted(Hotel $hotel): void
    {
        //
    }

    /**
     * Handle the Hotel "restored" event.
     */
    public function restored(Hotel $hotel): void
    {
        //
    }

    /**
     * Handle the Hotel "force deleted" event.
     */
    public function forceDeleted(Hotel $hotel): void
    {
        //
    }
}
