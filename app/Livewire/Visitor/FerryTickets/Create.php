<?php

namespace App\Livewire\Visitor\FerryTickets;

use App\Models\Ferry\FerrySchedule;
use App\Services\FerryTicketService;
use Livewire\Component;

class Create extends Component
{
    public FerrySchedule $schedule;
    public $travelDate;
    public $passengers;
    public $hotelBooking;
    public $totalPrice;

    public function mount(FerrySchedule $schedule)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->schedule = $schedule->load(['route', 'vessel']);
        $this->travelDate = request()->query('date');
        $this->passengers = request()->query('passengers', 1);

        // Validate hotel booking
        $service = app(FerryTicketService::class);
        $validation = $service->validateHotelBooking(auth()->id());

        if (!$validation['valid']) {
            session()->flash('error', $validation['errors'][0]);
            return redirect()->route('ferry-tickets.browse');
        }

        $this->hotelBooking = $validation['booking'];
        $this->totalPrice = $this->schedule->route->base_price * $this->passengers;
    }

    public function confirmBooking()
    {
        $service = app(FerryTicketService::class);

        try {
            $ticket = $service->createTicket([
                'guest_id' => auth()->id(),
                'hotel_booking_id' => $this->hotelBooking->id,
                'ferry_schedule_id' => $this->schedule->id,
                'travel_date' => $this->travelDate,
                'number_of_passengers' => $this->passengers,
                'payment_status' => 'paid',
                'payment_method' => 'online',
            ]);

            return redirect()->route('ferry-tickets.confirmation', $ticket->id);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.visitor.ferry-tickets.create')
            ->layout('layouts.visitor');
    }
}
