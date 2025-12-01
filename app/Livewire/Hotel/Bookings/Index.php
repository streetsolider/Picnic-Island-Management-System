<?php

namespace App\Livewire\Hotel\Bookings;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Services\BookingService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $hotel;

    // Filters
    public $search = '';
    public $filterStatus = '';
    public $filterRoomType = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    // Stats
    public $stats = [];

    // Cancel modal
    public $cancellingBookingId = null;
    public $cancellingBooking = null;
    public $cancellationReason = '';

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        $this->calculateStats();
    }

    public function calculateStats()
    {
        $bookingService = app(BookingService::class);

        // Get overall stats
        $allStats = $bookingService->getBookingStats($this->hotel);

        // Get today's check-ins and check-outs
        $today = Carbon::today()->format('Y-m-d');
        $todayCheckIns = $this->hotel->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', $today)
            ->count();

        $todayCheckOuts = $this->hotel->bookings()
            ->where('status', 'confirmed')
            ->where('check_out_date', $today)
            ->count();

        // Get upcoming bookings
        $upcomingBookings = $this->hotel->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '>', $today)
            ->count();

        // Get current guests (checked in)
        $currentGuests = $this->hotel->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '<=', $today)
            ->where('check_out_date', '>', $today)
            ->count();

        $this->stats = [
            'total_bookings' => $allStats['total_bookings'],
            'confirmed_bookings' => $allStats['confirmed_bookings'],
            'cancelled_bookings' => $allStats['cancelled_bookings'],
            'total_revenue' => $allStats['total_revenue'],
            'today_check_ins' => $todayCheckIns,
            'today_check_outs' => $todayCheckOuts,
            'upcoming_bookings' => $upcomingBookings,
            'current_guests' => $currentGuests,
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterRoomType()
    {
        $this->resetPage();
    }

    public function updatingFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatingFilterDateTo()
    {
        $this->resetPage();
    }

    public function openCancelModal($bookingId)
    {
        $this->cancellingBooking = HotelBooking::where('hotel_id', $this->hotel->id)
            ->with(['guest', 'room'])
            ->findOrFail($bookingId);
        $this->cancellingBookingId = $this->cancellingBooking->id;
        $this->cancellationReason = '';

        $this->dispatch('open-modal', 'cancel-booking');
    }

    public function closeCancelModal()
    {
        $this->cancellingBookingId = null;
        $this->cancellingBooking = null;
        $this->cancellationReason = '';
        $this->resetValidation();
        $this->dispatch('close-modal', 'cancel-booking');
    }

    public function confirmCancel()
    {
        $this->validate([
            'cancellationReason' => 'required|string|min:10|max:500',
        ], [
            'cancellationReason.required' => 'Please provide a reason for cancellation.',
            'cancellationReason.min' => 'Cancellation reason must be at least 10 characters.',
        ]);

        if (!$this->cancellingBookingId) {
            session()->flash('error', 'No booking selected for cancellation.');
            return;
        }

        $booking = HotelBooking::where('hotel_id', $this->hotel->id)->findOrFail($this->cancellingBookingId);

        if ($booking->isCancelled()) {
            session()->flash('error', 'This booking is already cancelled.');
            $this->closeCancelModal();
            return;
        }

        $bookingService = app(BookingService::class);
        $bookingService->cancelBooking($booking, $this->cancellationReason);

        $this->calculateStats();
        $this->closeCancelModal();
        session()->flash('success', "Booking {$booking->booking_reference} has been cancelled successfully.");
    }

    public function markAsNoShow($bookingId)
    {
        $booking = HotelBooking::where('hotel_id', $this->hotel->id)->findOrFail($bookingId);

        if ($booking->isCompleted() || $booking->isCancelled()) {
            session()->flash('error', 'Cannot mark this booking as no-show.');
            return;
        }

        $booking->markAsNoShow();
        $this->calculateStats();
        session()->flash('success', "Booking {$booking->booking_reference} marked as no-show.");
    }

    public function markAsCompleted($bookingId)
    {
        $booking = HotelBooking::where('hotel_id', $this->hotel->id)->findOrFail($bookingId);

        if ($booking->isCompleted() || $booking->isCancelled()) {
            session()->flash('error', 'Cannot complete this booking.');
            return;
        }

        $booking->complete();
        $this->calculateStats();
        session()->flash('success', "Booking {$booking->booking_reference} marked as completed.");
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $bookings = HotelBooking::where('hotel_id', $this->hotel->id)
            ->with(['guest', 'room'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('booking_reference', 'like', '%' . $this->search . '%')
                      ->orWhereHas('guest', function ($gq) {
                          $gq->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterRoomType, function ($query) {
                $query->whereHas('room', function ($q) {
                    $q->where('room_type', $this->filterRoomType);
                });
            })
            ->when($this->filterDateFrom, function ($query) {
                $query->where('check_in_date', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($query) {
                $query->where('check_out_date', '<=', $this->filterDateTo);
            })
            ->orderBy('check_in_date', 'desc')
            ->paginate(15);

        return view('livewire.hotel.bookings.index', [
            'bookings' => $bookings,
            'statuses' => ['confirmed', 'cancelled', 'completed', 'no-show'],
            'roomTypes' => ['standard', 'superior', 'deluxe', 'suite', 'family'],
        ]);
    }
}
