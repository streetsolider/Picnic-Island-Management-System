<?php

namespace App\Livewire\Beach;

use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Bookings Calendar')]
class BookingsCalendar extends Component
{
    public $selectedDate;
    public $selectedBooking = null;
    public $showBookingModal = false;

    public function mount()
    {
        $this->selectedDate = today()->toDateString();
    }

    public function changeDate($direction)
    {
        $date = Carbon::parse($this->selectedDate);

        if ($direction === 'prev') {
            $this->selectedDate = $date->subDay()->toDateString();
        } else {
            $this->selectedDate = $date->addDay()->toDateString();
        }
    }

    public function goToToday()
    {
        $this->selectedDate = today()->toDateString();
    }

    public function showBookingDetails($bookingId)
    {
        $this->selectedBooking = BeachServiceBooking::with(['guest', 'service', 'hotelBooking', 'redeemedByStaff'])
            ->find($bookingId);
        $this->showBookingModal = true;
    }

    public function closeModal()
    {
        $this->showBookingModal = false;
        $this->selectedBooking = null;
    }

    public function generateTimeSlots()
    {
        $staffId = auth('staff')->id();
        $assignedService = BeachService::where('assigned_staff_id', $staffId)->first();

        if (!$assignedService || !$assignedService->opening_time || !$assignedService->closing_time) {
            return [];
        }

        $slots = [];
        $currentTime = Carbon::parse($assignedService->opening_time);
        $closingTime = Carbon::parse($assignedService->closing_time);

        // Generate hourly time slots
        while ($currentTime->lessThan($closingTime)) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addHour();

            // If slot end exceeds closing time, use closing time
            if ($slotEnd->greaterThan($closingTime)) {
                $slotEnd = $closingTime->copy();
            }

            // Get bookings that overlap with this time slot
            $overlappingBookings = BeachServiceBooking::where('beach_service_id', $assignedService->id)
                ->where('booking_date', $this->selectedDate)
                ->whereIn('status', ['confirmed', 'redeemed', 'cancelled'])
                ->where(function ($query) use ($slotStart, $slotEnd) {
                    $startTime = $slotStart->format('H:i:s');
                    $endTime = $slotEnd->format('H:i:s');

                    $query->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime])
                          ->orWhere(function ($q) use ($startTime, $endTime) {
                              $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                          });
                })
                ->with(['guest'])
                ->get();

            $slots[] = [
                'time' => $slotStart->format('g:i A'),
                'timeValue' => $slotStart->format('H:i'),
                'bookings' => $overlappingBookings,
            ];

            $currentTime->addHour();
        }

        return $slots;
    }

    public function render()
    {
        $staffId = auth('staff')->id();

        // Get the selected service from session
        $selectedServiceId = session('beach_selected_service_id');

        // Get the beach service
        $assignedService = $selectedServiceId
            ? BeachService::where('id', $selectedServiceId)
                ->where('assigned_staff_id', $staffId)
                ->with('category')
                ->first()
            : BeachService::where('assigned_staff_id', $staffId)
                ->with('category')
                ->first();

        if (!$assignedService) {
            return view('livewire.beach.bookings-calendar', [
                'assignedService' => null,
                'timeSlots' => [],
                'selectedDateFormatted' => '',
                'isToday' => false,
            ]);
        }

        $timeSlots = $this->generateTimeSlots();
        $selectedDateCarbon = Carbon::parse($this->selectedDate);

        return view('livewire.beach.bookings-calendar', [
            'assignedService' => $assignedService,
            'timeSlots' => $timeSlots,
            'selectedDateFormatted' => $selectedDateCarbon->format('F j, Y'),
            'isToday' => $selectedDateCarbon->isToday(),
        ]);
    }
}
