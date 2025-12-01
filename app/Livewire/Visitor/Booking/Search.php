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
    // Basic search parameters
    public $checkIn;
    public $checkOut;
    public $guests = 2;

    // Advanced filters
    public $roomType = '';
    public $view = '';
    public $bedSize = '';
    public $bedCount = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'price_asc'; // price_asc, price_desc, rating_desc

    // UI state
    public $results = [];
    public $searched = false;
    public $showFilters = false;

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
        'roomType',
        'view',
        'bedSize',
        'bedCount',
        'sortBy',
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
            'guests' => 'required|integer|min:1|max:20',
            'minPrice' => 'nullable|numeric|min:0',
            'maxPrice' => 'nullable|numeric|min:0|gte:minPrice',
        ]);

        $this->results = [];
        $this->searched = true;

        // Get all active hotels
        $hotels = Hotel::active()->with(['rooms'])->get();

        $bookingService = app(BookingService::class);
        $pricingCalculator = app(PricingCalculator::class);

        foreach ($hotels as $hotel) {
            // Get available rooms for this hotel with all filters
            $availableRooms = $bookingService->getAvailableRooms(
                $hotel,
                $this->checkIn,
                $this->checkOut,
                $this->guests,
                $this->roomType ?: null,
                $this->view ?: null,
                $this->bedSize ?: null,
                $this->bedCount ?: null,
                $this->minPrice ? floatval($this->minPrice) : null,
                $this->maxPrice ? floatval($this->maxPrice) : null
            );

            if ($availableRooms->isNotEmpty()) {
                // Convert dates to Carbon instances
                $checkInDate = Carbon::parse($this->checkIn);
                $checkOutDate = Carbon::parse($this->checkOut);

                // Get unique room types (group by room configuration)
                $roomTypes = $availableRooms->groupBy(function ($room) {
                    return $room->room_type . '_' . $room->bed_size . '_' . $room->bed_count . '_' . $room->view;
                });

                // Get the cheapest room for this hotel
                $cheapestRoom = $availableRooms->sortBy(function ($room) use ($pricingCalculator, $checkInDate, $checkOutDate) {
                    $pricing = $pricingCalculator->calculateRoomPrice(
                        $room,
                        $checkInDate,
                        $checkOutDate
                    );
                    return $pricing['total_price'];
                })->first();

                $pricing = $pricingCalculator->calculateRoomPrice(
                    $cheapestRoom,
                    $checkInDate,
                    $checkOutDate
                );

                $this->results[] = [
                    'hotel' => $hotel,
                    'room_types_count' => $roomTypes->count(), // Count of unique room types
                    'total_rooms_available' => $availableRooms->count(), // Total individual rooms
                    'starting_price' => $pricing['total_price'],
                    'price_per_night' => $pricing['average_price_per_night'],
                    'nights' => $pricing['number_of_nights'],
                ];
            }
        }

        // Apply sorting
        $this->applySorting();
    }

    protected function applySorting()
    {
        usort($this->results, function ($a, $b) {
            return match($this->sortBy) {
                'price_asc' => $a['starting_price'] <=> $b['starting_price'],
                'price_desc' => $b['starting_price'] <=> $a['starting_price'],
                'rating_desc' => $b['hotel']->star_rating <=> $a['hotel']->star_rating,
                default => $a['starting_price'] <=> $b['starting_price'],
            };
        });
    }

    public function clearFilters()
    {
        $this->roomType = '';
        $this->view = '';
        $this->bedSize = '';
        $this->bedCount = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->sortBy = 'price_asc';
        $this->search();
    }

    public function render()
    {
        return view('livewire.visitor.booking.search')
            ->layout('layouts.visitor');
    }
}
