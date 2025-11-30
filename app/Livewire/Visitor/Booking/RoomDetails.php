<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\Room;
use App\Services\PricingCalculator;
use Carbon\Carbon;
use Livewire\Component;

class RoomDetails extends Component
{
    public Room $room;
    public $checkIn;
    public $checkOut;
    public $guests = 2;
    public $pricing = null;
    public $isAvailable = true;

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
    ];

    public function mount(Room $room)
    {
        $this->room = $room->load(['hotel', 'amenities.category', 'gallery.images']);

        // Set default dates if not provided
        if (!$this->checkIn) {
            $this->checkIn = Carbon::today()->addDays(1)->format('Y-m-d');
        }
        if (!$this->checkOut) {
            $this->checkOut = Carbon::today()->addDays(2)->format('Y-m-d');
        }

        $this->calculatePricing();
    }

    public function updated($property)
    {
        if (in_array($property, ['checkIn', 'checkOut', 'guests'])) {
            $this->calculatePricing();
        }
    }

    public function calculatePricing()
    {
        // Check availability
        $this->isAvailable = $this->room->isAvailableForDates($this->checkIn, $this->checkOut);

        if (!$this->isAvailable) {
            $this->pricing = null;
            return;
        }

        // Calculate pricing
        $pricingCalculator = app(PricingCalculator::class);
        $this->pricing = $pricingCalculator->calculateRoomPrice(
            $this->room,
            $this->checkIn,
            $this->checkOut
        );
    }

    public function bookNow()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            // Redirect to login with return URL
            return redirect()->route('login', [
                'redirect' => route('booking.create', [
                    'room' => $this->room->id,
                    'checkIn' => $this->checkIn,
                    'checkOut' => $this->checkOut,
                    'guests' => $this->guests,
                ])
            ]);
        }

        // Redirect to booking form
        return redirect()->route('booking.create', [
            'room' => $this->room->id,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'guests' => $this->guests,
        ]);
    }

    public function render()
    {
        return view('livewire.visitor.booking.room-details')
            ->layout('layouts.visitor');
    }
}
