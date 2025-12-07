<?php

namespace App\Livewire\Beach;

use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use App\Services\BeachValidationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Beach Staff Dashboard')]
class Dashboard extends Component
{
    public $selectedServiceId;

    public function mount()
    {
        $staffId = auth('staff')->id();

        // Get all services assigned to this staff member
        $assignedServices = BeachService::where('assigned_staff_id', $staffId)->get();

        if ($assignedServices->isEmpty()) {
            return;
        }

        // Check if there's a selected service in session
        $sessionServiceId = session('beach_selected_service_id');

        // Validate that the session service is still assigned to this staff
        if ($sessionServiceId && $assignedServices->contains('id', $sessionServiceId)) {
            $this->selectedServiceId = $sessionServiceId;
        } else {
            // Default to first assigned service
            $this->selectedServiceId = $assignedServices->first()->id;
            session(['beach_selected_service_id' => $this->selectedServiceId]);
        }
    }

    public function selectService($serviceId)
    {
        $staffId = auth('staff')->id();

        // Verify this service is assigned to this staff member
        $service = BeachService::where('id', $serviceId)
            ->where('assigned_staff_id', $staffId)
            ->first();

        if ($service) {
            $this->selectedServiceId = $serviceId;
            session(['beach_selected_service_id' => $serviceId]);

            session()->flash('success', 'Switched to ' . $service->name);
        }
    }

    public function render()
    {
        $staffId = auth('staff')->id();

        // Get all beach services assigned to this staff member
        $assignedServices = BeachService::where('assigned_staff_id', $staffId)
            ->with('category')
            ->get();

        // If no services assigned, show empty state
        if ($assignedServices->isEmpty()) {
            return view('livewire.beach.dashboard', [
                'assignedServices' => collect(),
                'selectedService' => null,
                'stats' => null,
                'todayBookings' => collect(),
                'upcomingBookings' => collect(),
            ]);
        }

        // Get the currently selected service
        $selectedService = $assignedServices->firstWhere('id', $this->selectedServiceId);

        // Get stats for the selected service
        $stats = null;
        if ($selectedService) {
            $todayBookingsCount = BeachServiceBooking::where('beach_service_id', $selectedService->id)
                ->where('booking_date', today())
                ->whereIn('status', ['confirmed', 'redeemed'])
                ->count();

            $todayRedemptionsCount = BeachServiceBooking::where('beach_service_id', $selectedService->id)
                ->where('booking_date', today())
                ->where('status', 'redeemed')
                ->count();

            $todayRevenue = BeachServiceBooking::where('beach_service_id', $selectedService->id)
                ->where('booking_date', today())
                ->where('status', 'redeemed')
                ->sum('total_price');

            $stats = [
                'today_bookings' => $todayBookingsCount,
                'today_redemptions' => $todayRedemptionsCount,
                'today_revenue' => $todayRevenue,
            ];
        }

        // Get today's bookings for selected service
        $todayBookings = $selectedService
            ? BeachServiceBooking::where('beach_service_id', $selectedService->id)
                ->where('booking_date', today())
                ->with(['guest', 'hotelBooking'])
                ->whereIn('status', ['confirmed', 'redeemed'])
                ->orderBy('start_time')
                ->get()
            : collect();

        // Get upcoming bookings (next 7 days, excluding today)
        $upcomingBookings = $selectedService
            ? BeachServiceBooking::where('beach_service_id', $selectedService->id)
                ->where('booking_date', '>', today())
                ->where('booking_date', '<=', today()->addDays(7))
                ->with(['guest', 'hotelBooking'])
                ->whereIn('status', ['confirmed', 'redeemed'])
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->limit(10)
                ->get()
            : collect();

        return view('livewire.beach.dashboard', [
            'assignedServices' => $assignedServices,
            'selectedService' => $selectedService,
            'stats' => $stats,
            'todayBookings' => $todayBookings,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
