<?php

namespace App\Livewire\Visitor\FerryTickets;

use App\Models\Ferry\FerryRoute;
use App\Services\FerryTicketService;
use Carbon\Carbon;
use Livewire\Component;

class Browse extends Component
{
    public $travelDate;
    public $routeId = '';
    public $passengers = 1;
    public $schedules = [];
    public $hasValidBooking = false;
    public $hotelBooking = null;
    public $routes;
    public $maxPassengers = 20;

    public function mount()
    {
        $this->travelDate = now()->addDay()->format('Y-m-d');
        $this->routes = FerryRoute::where('is_active', true)->get();

        if (auth()->check()) {
            $service = app(FerryTicketService::class);
            $validation = $service->validateHotelBooking(auth()->id());
            $this->hasValidBooking = $validation['valid'];
            $this->hotelBooking = $validation['booking'];

            // Set max passengers based on room occupancy
            if ($this->hotelBooking) {
                $this->maxPassengers = $this->hotelBooking->room->max_occupancy;
                // Ensure passengers doesn't exceed max
                if ($this->passengers > $this->maxPassengers) {
                    $this->passengers = $this->maxPassengers;
                }
            }
        } else {
            // Store intended URL for redirect after login
            session()->put('url.intended', url()->current());
        }
    }

    public function search()
    {
        $this->validate([
            'travelDate' => 'required|date|after_or_equal:today',
            'passengers' => 'required|integer|min:1|max:' . $this->maxPassengers,
        ], [
            'passengers.max' => 'Number of passengers cannot exceed your room\'s maximum occupancy (' . $this->maxPassengers . ' persons).',
        ]);

        // Validate travel date is within hotel booking date range
        if ($this->hotelBooking) {
            $checkIn = $this->hotelBooking->check_in_date->format('Y-m-d');
            $checkOut = $this->hotelBooking->check_out_date->format('Y-m-d');

            if ($this->travelDate < $checkIn || $this->travelDate > $checkOut) {
                session()->flash('error', "Travel date must be within your hotel stay ({$checkIn} to {$checkOut}).");
                return;
            }

            // Check booking status and provide helpful messages
            $hasArrival = $this->hotelBooking->hasArrivalFerry();
            $allPassengersDeparted = $this->hotelBooking->hasAllPassengersDeparted();

            if ($hasArrival && $allPassengersDeparted) {
                session()->flash('info', 'All passengers have already booked their departure ferries. No more ferry bookings needed for this hotel stay.');
                $this->schedules = collect();
                return;
            }
        }

        $service = app(FerryTicketService::class);
        $availableSchedules = $service->getAvailableSchedules(
            $this->travelDate,
            $this->routeId ?: null,
            $this->passengers
        );

        // Filter schedules based on booking direction
        if ($this->hotelBooking) {
            $hasArrival = $this->hotelBooking->hasArrivalFerry();

            $this->schedules = $availableSchedules->filter(function ($schedule) use ($hasArrival) {
                $isToIsland = $schedule->route->destination === 'Picnic Island';

                // If no arrival yet, only show routes TO island
                if (!$hasArrival) {
                    return $isToIsland;
                }

                // If has arrival, only show routes FROM island
                return !$isToIsland;
            })->values();
        } else {
            $this->schedules = $availableSchedules;
        }
    }

    public function render()
    {
        return view('livewire.visitor.ferry-tickets.browse')
            ->layout('layouts.visitor');
    }
}
