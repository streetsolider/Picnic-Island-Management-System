<?php

namespace App\Livewire\Visitor\BeachActivities;

use App\Models\BeachServiceBooking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.visitor')]
#[Title('My Beach Activity Bookings')]
class MyBookings extends Component
{
    use WithPagination;

    #[Url]
    public $tab = 'upcoming'; // upcoming, past, cancelled

    public $selectedBooking = null;
    public $showCancelModal = false;
    public $cancellationReason = '';

    public function switchTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function openCancelModal($bookingId)
    {
        $this->selectedBooking = BeachServiceBooking::findOrFail($bookingId);

        // Only allow cancellation of confirmed bookings
        if (!$this->selectedBooking->isConfirmed()) {
            session()->flash('error', 'Only confirmed bookings can be cancelled.');
            return;
        }

        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->selectedBooking = null;
        $this->cancellationReason = '';
    }

    public function confirmCancel()
    {
        if (!$this->selectedBooking) {
            return;
        }

        // Check if booking can still be cancelled (24 hours before)
        $bookingDateTime = $this->selectedBooking->booking_date->setTimeFromTimeString($this->selectedBooking->start_time);

        if ($bookingDateTime->lessThan(now()->addHours(24))) {
            session()->flash('error', 'Bookings cannot be cancelled less than 24 hours before the scheduled time.');
            $this->closeCancelModal();
            return;
        }

        $this->selectedBooking->cancel($this->cancellationReason ?: 'Cancelled by guest');

        session()->flash('success', 'Booking cancelled successfully.');
        $this->closeCancelModal();
    }

    public function render()
    {
        $query = BeachServiceBooking::where('guest_id', auth()->id())
            ->with(['service.category', 'hotelBooking']);

        // Filter by tab
        if ($this->tab === 'upcoming') {
            // Check both date and time to exclude past events on today's date
            $query->where(function ($q) {
                      $q->where('booking_date', '>', now()->toDateString())
                        ->orWhere(function ($subQ) {
                            $subQ->where('booking_date', '=', now()->toDateString())
                                 ->whereRaw("CONCAT(booking_date, ' ', start_time) >= ?", [now()->format('Y-m-d H:i:s')]);
                        });
                  })
                  ->whereIn('status', ['confirmed', 'redeemed'])
                  ->orderBy('booking_date')
                  ->orderBy('start_time');
        } elseif ($this->tab === 'past') {
            // Events are past if: date is before today OR (date is today AND time has passed)
            $query->where(function ($q) {
                      $q->where('booking_date', '<', now()->toDateString())
                        ->orWhere(function ($subQ) {
                            $subQ->where('booking_date', '=', now()->toDateString())
                                 ->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [now()->format('Y-m-d H:i:s')]);
                        });
                  })
                  ->whereIn('status', ['confirmed', 'redeemed'])
                  ->orderBy('booking_date', 'desc')
                  ->orderBy('start_time', 'desc');
        } elseif ($this->tab === 'cancelled') {
            $query->where('status', 'cancelled')
                  ->orderBy('booking_date', 'desc')
                  ->orderBy('start_time', 'desc');
        }

        $bookings = $query->paginate(10);

        return view('livewire.visitor.beach-activities.my-bookings', [
            'bookings' => $bookings,
        ]);
    }
}
