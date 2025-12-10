<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\Room;
use App\Models\HotelBooking;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Livewire\Component;

class Create extends Component
{
    public Room $room;
    public $checkIn;
    public $checkOut;
    public $guests = 2; // Default value
    public $specialRequests = '';
    public $pricing = null;
    public $errors = [];

    protected $queryString = [
        'checkIn',
        'checkOut',
        'guests',
    ];

    public function mount(Room $room)
    {
        // Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->room = $room->load(['hotel']);

        // Validate guest count against room capacity
        if ($this->guests > $this->room->max_occupancy) {
            session()->flash('error', "This room can accommodate a maximum of {$this->room->max_occupancy} guests. Please search for rooms that can accommodate {$this->guests} guests.");
            return redirect()->route('booking.search', [
                'checkIn' => $this->checkIn,
                'checkOut' => $this->checkOut,
                'guests' => $this->guests,
            ]);
        }

        // Calculate pricing
        $pricingCalculator = app(PricingCalculator::class);
        $this->pricing = $pricingCalculator->calculateRoomPrice(
            $this->room,
            \Carbon\Carbon::parse($this->checkIn),
            \Carbon\Carbon::parse($this->checkOut)
        );
    }

    public function confirmBooking()
    {
        try {
            // Store booking data in session for payment page
            session()->put('pending_booking', [
                'booking_type' => 'hotel',
                'room_id' => $this->room->id,
                'guest_id' => auth()->id(),
                'check_in_date' => $this->checkIn,
                'check_out_date' => $this->checkOut,
                'number_of_guests' => $this->guests,
                'number_of_rooms' => 1,
                'special_requests' => $this->specialRequests,
                'total_price' => $this->pricing['total_price'],
                'hotel_name' => $this->room->hotel->name,
                'room_number' => $this->room->room_number,
            ]);

            // Redirect to payment gateway
            return redirect()->route('payment.gateway');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.visitor.booking.create')
            ->layout('layouts.visitor');
    }
}
