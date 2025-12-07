<?php

namespace App\Livewire\Visitor\BeachActivities;

use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use App\Services\BeachBookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('layouts.visitor')]
#[Title('Complete Beach Activity Booking')]
class Create extends Component
{
    public BeachService $service;
    public $hotelBooking;

    public $selectedDate;
    public $startTime;
    public $endTime;
    public $pricing;

    #[Validate('required')]
    public $paymentMethod = 'card';

    #[Validate('accepted')]
    public $termsAccepted = false;

    public function mount()
    {
        // Get parameters from query string
        $this->service = BeachService::with('category')->findOrFail(request('service'));
        $this->selectedDate = request('date');
        $this->startTime = request('start');
        $this->endTime = request('end');

        // Validate hotel booking
        $bookingService = app(BeachBookingService::class);
        $validation = $bookingService->validateHotelBooking(auth()->id());

        if (!$validation['valid']) {
            session()->flash('error', $validation['errors'][0]);
            return redirect()->route('booking.search');
        }

        $this->hotelBooking = $validation['booking'];

        // Check if guest is checked in (SECURITY: prevent booking before check-in)
        $today = today();
        $isCheckedIn = $this->hotelBooking->check_in_date->lessThanOrEqualTo($today)
                    && $this->hotelBooking->check_out_date->greaterThan($today);

        if (!$isCheckedIn) {
            session()->flash('error', 'You must be checked into your hotel to book beach activities.');
            return redirect()->route('visitor.beach-activities.details', $this->service);
        }

        // Validate booking date is within hotel stay (SECURITY: prevent URL manipulation)
        $bookingDate = Carbon::parse($this->selectedDate);
        $maxAllowedDate = $this->hotelBooking->check_out_date->copy()->subDay();

        if ($bookingDate->lessThan($today) || $bookingDate->greaterThan($maxAllowedDate)) {
            session()->flash('error', 'Invalid booking date. You can only book for dates during your hotel stay (before check-out day).');
            return redirect()->route('visitor.beach-activities.details', $this->service);
        }

        // Calculate pricing
        $this->pricing = $bookingService->calculatePrice(
            $this->service,
            $this->startTime,
            $this->endTime
        );

        // Validate availability
        $isAvailable = $this->service->isAvailable(
            $this->selectedDate,
            $this->startTime,
            $this->endTime
        );

        if (!$isAvailable) {
            session()->flash('error', 'This time slot is no longer available. Please select another slot.');
            return redirect()->route('visitor.beach-activities.details', $this->service);
        }
    }

    public function confirmBooking()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $bookingService = app(BeachBookingService::class);

            // Calculate duration in hours for flexible duration
            $durationHours = null;
            if ($this->service->isFlexibleDuration()) {
                $start = Carbon::parse($this->selectedDate . ' ' . $this->startTime);
                $end = Carbon::parse($this->selectedDate . ' ' . $this->endTime);
                $durationHours = $start->diffInHours($end);
            }

            $booking = $bookingService->createBooking([
                'guest_id' => auth()->id(),
                'beach_service_id' => $this->service->id,
                'hotel_booking_id' => $this->hotelBooking->id,
                'booking_date' => $this->selectedDate,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'duration_hours' => $durationHours,
                'price_per_unit' => $this->pricing['price_per_unit'],
                'total_price' => $this->pricing['total_price'],
                'payment_status' => 'paid', // For now, mark as paid
            ]);

            DB::commit();

            session()->flash('success', 'Beach activity booking confirmed successfully!');
            return redirect()->route('visitor.beach-activities.confirmation', $booking);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create booking. Please try again.');
            return;
        }
    }

    public function render()
    {
        $guest = auth()->user();
        $bookingDate = Carbon::parse($this->selectedDate);

        return view('livewire.visitor.beach-activities.create', [
            'guest' => $guest,
            'bookingDate' => $bookingDate,
        ]);
    }
}
