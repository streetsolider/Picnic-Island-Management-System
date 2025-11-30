<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\Hotel;
use App\Models\Room;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Carbon\Carbon;
use Livewire\Component;

class Search extends Component
{
    public $checkIn;
    public $checkOut;
    public $guests = 2;
    public $roomType = '';
    public $view = '';
    public $priceRange = '';

    public $results = [];
    public $searched = false;
    public $showFilters = false;

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
        'roomType',
        'view',
    ];

    public function mount()
    {
        // Set default dates if not provided
        if (!$this->checkIn) {
            $this->checkIn = Carbon::today()->addDays(1)->format('Y-m-d');
        }
        if (!$this->checkOut) {
            $this->checkOut = Carbon::today()->addDays(2)->format('Y-m-d');
        }

        // Auto-search if query parameters exist
        if (request()->has('checkIn') && request()->has('checkOut')) {
            $this->search();
        }
    }

    public function search()
    {
        $this->validate([
            'checkIn' => 'required|date|after_or_equal:today',
            'checkOut' => 'required|date|after:checkIn',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        $this->results = [];
        $this->searched = true;

        // Get all active hotels
        $hotels = Hotel::active()->with(['rooms'])->get();

        $bookingService = app(BookingService::class);
        $pricingCalculator = app(PricingCalculator::class);

        foreach ($hotels as $hotel) {
            // Get available rooms for this hotel
            $availableRooms = $bookingService->getAvailableRooms(
                $hotel,
                $this->checkIn,
                $this->checkOut,
                $this->roomType ?: null,
                $this->view ?: null
            );

            if ($availableRooms->isNotEmpty()) {
                // Get the cheapest room for this hotel
                $cheapestRoom = $availableRooms->sortBy(function ($room) use ($pricingCalculator) {
                    $pricing = $pricingCalculator->calculateRoomPrice(
                        $room,
                        $this->checkIn,
                        $this->checkOut
                    );
                    return $pricing['total'];
                })->first();

                $pricing = $pricingCalculator->calculateRoomPrice(
                    $cheapestRoom,
                    $this->checkIn,
                    $this->checkOut
                );

                $this->results[] = [
                    'hotel' => $hotel,
                    'cheapest_room' => $cheapestRoom,
                    'available_rooms_count' => $availableRooms->count(),
                    'starting_price' => $pricing['total'],
                    'price_per_night' => $pricing['total'] / $pricing['nights'],
                    'nights' => $pricing['nights'],
                ];
            }
        }

        // Sort by price
        usort($this->results, function ($a, $b) {
            return $a['starting_price'] <=> $b['starting_price'];
        });
    }

    public function render()
    {
        return view('livewire.visitor.booking.search')
            ->layout('layouts.visitor');
    }
}
