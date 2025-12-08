<?php

namespace App\Livewire\Hotel\Bookings;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $hotel;
    public $booking;

    // Cancel modal
    public $showCancelModal = false;
    public $cancellationReason = '';

    public function mount(HotelBooking $booking)
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Verify the booking belongs to the manager's hotel
        if ($booking->hotel_id !== $this->hotel->id) {
            abort(403, 'This booking does not belong to your hotel.');
        }

        // Load relationships
        $booking->load(['guest', 'room.amenities']);

        $this->booking = $booking;
    }

    public function openCancelModal()
    {
        if ($this->booking->isCancelled() || $this->booking->isCompleted()) {
            session()->flash('error', 'This booking cannot be cancelled.');
            return;
        }

        $this->cancellationReason = '';
        $this->resetValidation();
        $this->dispatch('open-modal', 'cancel-booking');
    }

    public function closeCancelModal()
    {
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

        if ($this->booking->isCancelled()) {
            session()->flash('error', 'This booking is already cancelled.');
            $this->closeCancelModal();
            return;
        }

        $bookingService = app(BookingService::class);
        $bookingService->cancelBooking($this->booking, $this->cancellationReason);

        $this->booking->refresh();
        $this->closeCancelModal();
        session()->flash('success', "Booking {$this->booking->booking_reference} has been cancelled successfully.");
    }

    public function markAsNoShow()
    {
        if ($this->booking->isCompleted() || $this->booking->isCancelled()) {
            session()->flash('error', 'Cannot mark this booking as no-show.');
            return;
        }

        $this->booking->markAsNoShow();
        $this->booking->refresh();
        session()->flash('success', "Booking {$this->booking->booking_reference} marked as no-show.");
    }

    public function markAsCompleted()
    {
        if ($this->booking->isCompleted() || $this->booking->isCancelled()) {
            session()->flash('error', 'Cannot complete this booking.');
            return;
        }

        $this->booking->complete();
        $this->booking->refresh();
        session()->flash('success', "Booking {$this->booking->booking_reference} marked as completed.");
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.bookings.show');
    }
}
