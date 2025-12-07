<?php

namespace App\Livewire\Hotel\Operations;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\LateCheckoutRequest;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $hotel;
    public $selectedBooking = null;

    // Check-in form
    public $checkInNotes = '';

    // Check-out form
    public $checkOutNotes = '';

    // Late checkout approval
    public $selectedLateCheckoutRequest = null;
    public $lateCheckoutManagerNotes = '';

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
            ->with(['guest', 'room', 'hotel', 'lateCheckoutRequest'])
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

    public function getPendingLateCheckoutRequestsProperty()
    {
        return LateCheckoutRequest::whereHas('booking', function($query) {
                $query->where('hotel_id', $this->hotel->id);
            })
            ->with(['booking.guest', 'booking.room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Check-in operations
    public function openCheckInModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkInNotes = '';
        $this->dispatch('open-modal', 'check-in');
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
        $this->dispatch('close-modal', 'check-in');
        $this->selectedBooking = null;
        $this->checkInNotes = '';
    }

    // Check-out operations
    public function openCheckOutModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkOutNotes = '';
        $this->dispatch('open-modal', 'check-out');
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
        $this->dispatch('close-modal', 'check-out');
        $this->selectedBooking = null;
        $this->checkOutNotes = '';
    }

    // Late checkout approval operations
    public function openApproveLateCheckoutModal($requestId)
    {
        $this->selectedLateCheckoutRequest = LateCheckoutRequest::with(['booking.guest', 'booking.room', 'booking.hotel'])
            ->findOrFail($requestId);
        $this->lateCheckoutManagerNotes = '';
        $this->dispatch('open-modal', 'approve-late-checkout');
    }

    public function openRejectLateCheckoutModal($requestId)
    {
        $this->selectedLateCheckoutRequest = LateCheckoutRequest::with(['booking.guest', 'booking.room', 'booking.hotel'])
            ->findOrFail($requestId);
        $this->lateCheckoutManagerNotes = '';
        $this->dispatch('open-modal', 'reject-late-checkout');
    }

    public function approveLateCheckout()
    {
        $this->validate([
            'lateCheckoutManagerNotes' => 'nullable|string|max:500',
        ]);

        $this->selectedLateCheckoutRequest->approve(
            auth('staff')->id(),
            $this->lateCheckoutManagerNotes
        );

        session()->flash('success', 'Late checkout request approved successfully!');
        $this->dispatch('close-modal', 'approve-late-checkout');
        $this->selectedLateCheckoutRequest = null;
        $this->lateCheckoutManagerNotes = '';
    }

    public function rejectLateCheckout()
    {
        $this->validate([
            'lateCheckoutManagerNotes' => 'required|string|max:500',
        ], [
            'lateCheckoutManagerNotes.required' => 'Please provide a reason for rejection.',
        ]);

        $this->selectedLateCheckoutRequest->reject(
            auth('staff')->id(),
            $this->lateCheckoutManagerNotes
        );

        session()->flash('success', 'Late checkout request rejected.');
        $this->dispatch('close-modal', 'reject-late-checkout');
        $this->selectedLateCheckoutRequest = null;
        $this->lateCheckoutManagerNotes = '';
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
            'pendingLateCheckoutRequests' => $this->pendingLateCheckoutRequests,
        ])->layout('layouts.hotel');
    }
}
