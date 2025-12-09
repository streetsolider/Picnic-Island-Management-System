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

    // Filters passed through from search
    public $roomType = '';
    public $view = '';
    public $bedSize = '';
    public $bedCount = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'price_asc';

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
        'roomType',
        'view',
        'bedSize',
        'bedCount',
        'minPrice',
        'maxPrice',
        'sortBy',
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

        // Validate guest count against room capacity
        if ($this->guests > $this->room->max_occupancy) {
            // Adjust guests to room capacity
            $this->guests = $this->room->max_occupancy;
        }

        $this->calculatePricing();
    }

    public function updated($property)
    {
        if (in_array($property, ['checkIn', 'checkOut', 'guests'])) {
            // Validate guest count
            if ($property === 'guests' && $this->guests > $this->room->max_occupancy) {
                $this->guests = $this->room->max_occupancy;
                session()->flash('warning', "This room can accommodate a maximum of {$this->room->max_occupancy} guests.");
            }

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
            Carbon::parse($this->checkIn),
            Carbon::parse($this->checkOut)
        );
    }

    public function bookNow()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            // Store intended URL and redirect to login
            $intendedUrl = route('booking.create', [
                'room' => $this->room->id,
                'checkIn' => $this->checkIn,
                'checkOut' => $this->checkOut,
                'guests' => $this->guests,
            ]);

            // Use Laravel's session put for url.intended
            session()->put('url.intended', $intendedUrl);
            session()->save(); // Ensure session is saved before redirect

            return $this->redirect(route('login'), navigate: true);
        }

        // Redirect to booking form
        return $this->redirect(route('booking.create', [
            'room' => $this->room->id,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'guests' => $this->guests,
        ]), navigate: true);
    }

    public function render()
    {
        return view('livewire.visitor.booking.room-details')
            ->layout('layouts.visitor');
    }
}
