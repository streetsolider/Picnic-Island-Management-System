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
    public $guests;
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

        // Calculate pricing
        $pricingCalculator = app(PricingCalculator::class);
        $this->pricing = $pricingCalculator->calculateRoomPrice(
            $this->room,
            $this->checkIn,
            $this->checkOut
        );
    }

    public function confirmBooking()
    {
        $bookingService = app(BookingService::class);

        try {
            // Create booking
            $booking = $bookingService->createBooking([
                'room_id' => $this->room->id,
                'guest_id' => auth()->id(),
                'check_in_date' => $this->checkIn,
                'check_out_date' => $this->checkOut,
                'number_of_guests' => $this->guests,
                'number_of_rooms' => 1,
                'special_requests' => $this->specialRequests,
                'payment_status' => 'paid', // In real app, this would be after payment
                'payment_method' => 'online', // Placeholder
            ]);

            // Redirect to confirmation page
            return redirect()->route('booking.confirmation', $booking->id);

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
