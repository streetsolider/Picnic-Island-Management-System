<?php

namespace App\Livewire\Hotel\Operations;

use App\Models\Hotel;
use App\Models\HotelBooking;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $hotel;
    public $showCheckInModal = false;
    public $showCheckOutModal = false;
    public $selectedBooking = null;

    // Check-in form
    public $checkInNotes = '';

    // Check-out form
    public $checkOutNotes = '';

    public function mount()
    {
        // Get the hotel for the logged-in manager
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->firstOrFail();
    }

    public function getTodayArrivalsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', Carbon::today())
            ->orderBy('check_in_date')
            ->get();
    }

    public function getTodayDeparturesProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'checked_in')
            ->whereDate('check_out_date', Carbon::today())
            ->orderBy('check_out_date')
            ->get();
    }

    public function getInHouseGuestsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'checked_in')
            ->get();
    }

    public function getUpcomingBookingsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', '>', Carbon::today())
            ->whereDate('check_in_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('check_in_date')
            ->get();
    }

    public function getOccupancyStatsProperty()
    {
        $totalRooms = $this->hotel->rooms()->where('is_active', true)->count();
        $occupiedRooms = $this->hotel->bookings()
            ->where('status', 'checked_in')
            ->count();

        return [
            'total' => $totalRooms,
            'occupied' => $occupiedRooms,
            'available' => $totalRooms - $occupiedRooms,
            'rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0,
        ];
    }

    public function getTodayRevenueProperty()
    {
        return $this->hotel->bookings()
            ->whereDate('check_in_date', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('total_price');
    }

    // Check-in operations
    public function openCheckInModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkInNotes = '';
        $this->showCheckInModal = true;
    }

    public function confirmCheckIn()
    {
        $this->validate([
            'checkInNotes' => 'nullable|string|max:1000',
        ]);

        $this->selectedBooking->checkIn(
            auth('staff')->id(),
            $this->checkInNotes
        );

        session()->flash('success', 'Guest checked in successfully!');
        $this->showCheckInModal = false;
        $this->selectedBooking = null;
        $this->checkInNotes = '';
    }

    // Check-out operations
    public function openCheckOutModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkOutNotes = '';
        $this->showCheckOutModal = true;
    }

    public function confirmCheckOut()
    {
        $this->validate([
            'checkOutNotes' => 'nullable|string|max:1000',
        ]);

        $this->selectedBooking->checkOut(
            auth('staff')->id(),
            $this->checkOutNotes
        );

        session()->flash('success', 'Guest checked out successfully!');
        $this->showCheckOutModal = false;
        $this->selectedBooking = null;
        $this->checkOutNotes = '';
    }

    public function render()
    {
        return view('livewire.hotel.operations.dashboard', [
            'todayArrivals' => $this->todayArrivals,
            'todayDepartures' => $this->todayDepartures,
            'inHouseGuests' => $this->inHouseGuests,
            'upcomingBookings' => $this->upcomingBookings,
            'occupancyStats' => $this->occupancyStats,
            'todayRevenue' => $this->todayRevenue,
        ])->layout('layouts.hotel');
    }
}
