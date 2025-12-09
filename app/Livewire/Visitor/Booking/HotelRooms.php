<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\Hotel;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Carbon\Carbon;
use Livewire\Component;

class HotelRooms extends Component
{
    public Hotel $hotel;
    public $checkIn;
    public $checkOut;
    public $guests = 2;
    public $sortBy = 'price_asc'; // price_asc, price_desc, type_asc

    // Filters from search page
    public $roomType = '';
    public $view = '';
    public $bedSize = '';
    public $bedCount = '';
    public $minPrice = '';
    public $maxPrice = '';

    public $roomTypes = [];

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
        'sortBy',
        'roomType',
        'view',
        'bedSize',
        'bedCount',
        'minPrice',
        'maxPrice',
    ];

    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;

        // Set default dates if not provided
        if (!$this->checkIn) {
            $this->checkIn = Carbon::today()->addDays(1)->format('Y-m-d');
        }
        if (!$this->checkOut) {
            $this->checkOut = Carbon::today()->addDays(2)->format('Y-m-d');
        }

        $this->loadRoomTypes();
    }

    public function loadRoomTypes()
    {
        $bookingService = app(BookingService::class);
        $pricingCalculator = app(PricingCalculator::class);

        // Get all available rooms for this hotel with filters applied
        $availableRooms = $bookingService->getAvailableRooms(
            $this->hotel,
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

        // Group rooms by their unique configuration
        $groupedRooms = $availableRooms->groupBy(function ($room) {
            return $room->room_type . '|' . $room->bed_size . '|' . $room->bed_count . '|' . $room->view;
        });

        // Convert dates to Carbon instances
        $checkInDate = Carbon::parse($this->checkIn);
        $checkOutDate = Carbon::parse($this->checkOut);

        // Build room types array with pricing
        $this->roomTypes = $groupedRooms->map(function ($rooms, $key) use ($pricingCalculator, $checkInDate, $checkOutDate) {
            $firstRoom = $rooms->first();

            // Calculate pricing for this room type
            $pricing = $pricingCalculator->calculateRoomPrice(
                $firstRoom,
                $checkInDate,
                $checkOutDate
            );

            return [
                'room' => $firstRoom,
                'available_count' => $rooms->count(),
                'pricing' => $pricing,
            ];
        })->values()->all();

        // Apply sorting
        $this->applySorting();
    }

    protected function applySorting()
    {
        usort($this->roomTypes, function ($a, $b) {
            return match($this->sortBy) {
                'price_asc' => $a['pricing']['total_price'] <=> $b['pricing']['total_price'],
                'price_desc' => $b['pricing']['total_price'] <=> $a['pricing']['total_price'],
                'type_asc' => $a['room']->room_type <=> $b['room']->room_type,
                default => $a['pricing']['total_price'] <=> $b['pricing']['total_price'],
            };
        });
    }

    public function updatedSortBy()
    {
        $this->applySorting();
    }

    public function render()
    {
        return view('livewire.visitor.booking.hotel-rooms')
            ->layout('layouts.visitor');
    }
}
