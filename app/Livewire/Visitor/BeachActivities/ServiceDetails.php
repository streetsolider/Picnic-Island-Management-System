<?php

namespace App\Livewire\Visitor\BeachActivities;

use App\Models\BeachService;
use App\Services\BeachBookingService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Layout('layouts.visitor')]
#[Title('Beach Activity Details')]
class ServiceDetails extends Component
{
    public BeachService $service;
    public $hotelBooking;
    public $isCheckedIn = false;
    public $minDate;
    public $maxDate;

    public $selectedDate = '';
    public $selectedSlot = '';
    public $selectedStartTime = '';
    public $selectedEndTime = '';
    public $durationHours = 1;

    public $availableSlots = [];
    public $availableStartTimes = [];

    public function mount(BeachService $service)
    {
        $this->service = $service->load('category');

        // Check if user is authenticated
        if (!auth()->check()) {
            // Not logged in - show login required message
            return;
        }

        // Validate hotel booking and check-in status
        $bookingService = app(BeachBookingService::class);
        $validation = $bookingService->validateHotelBooking(auth()->id());

        if (!$validation['valid']) {
            // No valid hotel booking - show booking required message
            return;
        }

        $this->hotelBooking = $validation['booking'];

        // Check if guest is checked in (check_in_date <= today <= check_out_date)
        $today = today();
        $this->isCheckedIn = $this->hotelBooking->check_in_date->lessThanOrEqualTo($today)
                          && $this->hotelBooking->check_out_date->greaterThanOrEqualTo($today);

        if (!$this->isCheckedIn) {
            // Not checked in yet - don't load slots
            return;
        }

        // Set date range: today to day before check-out
        // (guests check out on check_out_date, so can't book activities that day)
        $this->minDate = $today->toDateString();
        $this->maxDate = $this->hotelBooking->check_out_date->subDay()->toDateString();

        // Set minimum date to today (since already checked in)
        $this->selectedDate = $today->toDateString();
        $this->loadAvailableSlots();
    }

    public function updatedSelectedDate()
    {
        $this->reset(['selectedSlot', 'selectedStartTime', 'selectedEndTime']);
        $this->loadAvailableSlots();
    }

    public function updatedSelectedStartTime()
    {
        if ($this->service->isFlexibleDuration() && $this->selectedStartTime) {
            $this->calculateEndTime();
        }
    }

    public function updatedDurationHours()
    {
        if ($this->service->isFlexibleDuration() && $this->selectedStartTime) {
            $this->calculateEndTime();
        }
    }

    public function loadAvailableSlots()
    {
        if (!$this->selectedDate) {
            return;
        }

        $bookingService = app(BeachBookingService::class);
        $this->availableSlots = $bookingService->getAvailableSlots($this->service, $this->selectedDate);

        if ($this->service->isFlexibleDuration()) {
            $this->availableStartTimes = $this->generateStartTimes();
        }
    }

    public function selectSlot($slotTime)
    {
        $this->selectedSlot = $slotTime;

        // Parse the slot time to get start and end times
        $slotStart = Carbon::parse($this->selectedDate . ' ' . $slotTime);
        $this->selectedStartTime = $slotStart->format('H:i');
        $this->selectedEndTime = $slotStart->addMinutes($this->service->slot_duration_minutes)->format('H:i');
    }

    public function calculateEndTime()
    {
        $startTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedStartTime);
        $this->selectedEndTime = $startTime->addHours($this->durationHours)->format('H:i');
    }

    public function generateStartTimes()
    {
        $times = [];
        $current = Carbon::parse($this->service->opening_time);
        $closing = Carbon::parse($this->service->closing_time);

        // For flexible duration, generate start times in 30-minute intervals
        while ($current->lessThan($closing)) {
            $times[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $times;
    }

    #[Computed]
    public function calculatedPrice()
    {
        if (!$this->selectedStartTime || !$this->selectedEndTime) {
            return null;
        }

        $bookingService = app(BeachBookingService::class);
        return $bookingService->calculatePrice(
            $this->service,
            $this->selectedStartTime,
            $this->selectedEndTime
        );
    }

    public function proceedToBooking()
    {
        // Check if checked in
        if (!$this->isCheckedIn) {
            session()->flash('error', 'You must be checked into your hotel to book beach activities.');
            return;
        }

        // Validate that a slot or time is selected
        if ($this->service->isFixedSlot() && !$this->selectedSlot) {
            session()->flash('error', 'Please select a time slot.');
            return;
        }

        if ($this->service->isFlexibleDuration() && (!$this->selectedStartTime || !$this->selectedEndTime)) {
            session()->flash('error', 'Please select a start time and duration.');
            return;
        }

        // Date validation is already enforced by min/max in the date picker
        // But double-check for security
        $selectedDate = Carbon::parse($this->selectedDate);
        $maxAllowedDate = $this->hotelBooking->check_out_date->copy()->subDay();

        if ($selectedDate->lessThan(today()) || $selectedDate->greaterThan($maxAllowedDate)) {
            session()->flash('error', 'Invalid booking date. Please select a date during your hotel stay (before check-out day).');
            return;
        }

        // Redirect to create with parameters
        return redirect()->route('visitor.beach-activities.create', [
            'service' => $this->service->id,
            'date' => $this->selectedDate,
            'start' => $this->selectedStartTime,
            'end' => $this->selectedEndTime,
        ]);
    }

    public function render()
    {
        return view('livewire.visitor.beach-activities.service-details');
    }
}
