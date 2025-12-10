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
    public $selectedServiceId;
    public $selectedBooking = null;
    public $showBookingModal = false;

    public function mount()
    {
        $this->selectedDate = today()->toDateString();

        // Get selected service from session or default to first assigned service
        $staffId = auth('staff')->id();
        $this->selectedServiceId = session(
            'beach_selected_service_id',
            BeachService::where('assigned_staff_id', $staffId)->value('id')
        );
    }

    public function updatedSelectedServiceId($value)
    {
        session(['beach_selected_service_id' => $value]);
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

    public function render()
    {
        $staffId = auth('staff')->id();

        // Get all services assigned to this staff
        $allServices = BeachService::where('assigned_staff_id', $staffId)
            ->with('category')
            ->get();

        // Get the currently selected service
        $assignedService = $allServices->firstWhere('id', $this->selectedServiceId);

        if (!$assignedService) {
            return view('livewire.beach.bookings-calendar', [
                'assignedService' => null,
                'allServices' => $allServices,
                'bookings' => [],
                'timeGrid' => [],
                'selectedDateFormatted' => '',
                'isToday' => false,
            ]);
        }

        $selectedDateCarbon = Carbon::parse($this->selectedDate);

        // Calculate day boundaries based on service hours
        $openingTime = $assignedService->opening_time ? Carbon::parse($assignedService->opening_time) : Carbon::parse('06:00:00');
        $closingTime = $assignedService->closing_time ? Carbon::parse($assignedService->closing_time) : Carbon::parse('18:00:00');

        // Ensure closing time is after opening time
        if ($closingTime->lessThanOrEqualTo($openingTime)) {
            $closingTime->addDay();
        }

        $startHour = $openingTime->hour;
        $endHour = $closingTime->minute > 0 ? $closingTime->hour + 1 : $closingTime->hour;
        $totalMinutes = ($endHour - $startHour) * 60;

        // Fetch bookings for the day
        $bookings = BeachServiceBooking::where('beach_service_id', $assignedService->id)
            ->where('booking_date', $this->selectedDate)
            ->whereIn('status', ['confirmed', 'redeemed', 'cancelled'])
            ->with(['guest'])
            ->orderBy('start_time')
            ->get()
            ->map(function ($booking) use ($openingTime, $startHour) {
                $start = Carbon::parse($booking->start_time);
                $end = Carbon::parse($booking->end_time);

                // Calculate position relative to start of the calendar day
                // We use the startHour of the calendar as the 0 point
                $startMinutesFromDayStart = ($start->hour * 60 + $start->minute) - ($startHour * 60);
                $durationMinutes = $start->diffInMinutes($end);

                return [
                    'booking' => $booking,
                    'top_offset_minutes' => max(0, $startMinutesFromDayStart),
                    'duration_minutes' => $durationMinutes,
                ];
            });

        return view('livewire.beach.bookings-calendar', [
            'assignedService' => $assignedService,
            'allServices' => $allServices,
            'bookings' => $bookings,
            'startHour' => $startHour,
            'endHour' => $endHour,
            'totalMinutes' => $totalMinutes,
            'selectedDateFormatted' => $selectedDateCarbon->format('F j, Y'),
            'isToday' => $selectedDateCarbon->isToday(),
        ]);
    }
}
