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
        $this->selectedServiceId = session('beach_selected_service_id',
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

    public function generateTimeSlots()
    {
        $assignedService = BeachService::find($this->selectedServiceId);

        \Log::info('BookingsCalendar::generateTimeSlots called', [
            'selected_service_id' => $this->selectedServiceId,
            'assigned_service_id' => $assignedService?->id,
            'selected_date' => $this->selectedDate,
        ]);

        if (!$assignedService || !$assignedService->opening_time || !$assignedService->closing_time) {
            \Log::info('No service or no operating hours');
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

            $startTime = $slotStart->format('H:i:s');
            $endTime = $slotEnd->format('H:i:s');

            \Log::info('Checking slot', [
                'slot_time' => $slotStart->format('g:i A'),
                'slot_start' => $startTime,
                'slot_end' => $endTime,
                'date' => $this->selectedDate,
            ]);

            // Get bookings that START in this time slot (not overlapping)
            // This ensures each booking appears only once at its start time
            $overlappingBookings = BeachServiceBooking::where('beach_service_id', $assignedService->id)
                ->where('booking_date', $this->selectedDate)
                ->whereIn('status', ['confirmed', 'redeemed', 'cancelled'])
                ->where('start_time', '>=', $startTime)
                ->where('start_time', '<', $endTime)
                ->with(['guest'])
                ->get();

            \Log::info('Bookings found for slot', [
                'slot_time' => $slotStart->format('g:i A'),
                'count' => $overlappingBookings->count(),
                'booking_ids' => $overlappingBookings->pluck('id')->toArray(),
                'booking_times' => $overlappingBookings->map(function($b) {
                    return $b->start_time . ' - ' . $b->end_time;
                })->toArray(),
            ]);

            // Add duration info to each booking
            $bookingsWithDuration = $overlappingBookings->map(function ($booking) {
                $start = Carbon::parse($booking->start_time);
                $end = Carbon::parse($booking->end_time);
                $durationHours = $start->diffInHours($end);
                $durationMinutes = $start->diffInMinutes($end);

                return [
                    'booking' => $booking,
                    'duration_hours' => $durationHours,
                    'duration_minutes' => $durationMinutes,
                    'height_multiplier' => max(1, $durationHours), // Minimum 1 hour height
                ];
            });

            $slots[] = [
                'time' => $slotStart->format('g:i A'),
                'timeValue' => $slotStart->format('H:i'),
                'bookings' => $bookingsWithDuration,
            ];

            $currentTime->addHour();
        }

        \Log::info('Total slots generated', ['count' => count($slots)]);

        return $slots;
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
                'timeSlots' => [],
                'selectedDateFormatted' => '',
                'isToday' => false,
            ]);
        }

        $timeSlots = $this->generateTimeSlots();
        $selectedDateCarbon = Carbon::parse($this->selectedDate);

        return view('livewire.beach.bookings-calendar', [
            'assignedService' => $assignedService,
            'allServices' => $allServices,
            'timeSlots' => $timeSlots,
            'selectedDateFormatted' => $selectedDateCarbon->format('F j, Y'),
            'isToday' => $selectedDateCarbon->isToday(),
        ]);
    }
}
