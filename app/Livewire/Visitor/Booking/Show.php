<?php

namespace App\Livewire\Visitor\Booking;

use App\Models\HotelBooking;
use App\Services\LateCheckoutService;
use Livewire\Component;

class Show extends Component
{
    public HotelBooking $booking;

    // Late Checkout Request
    public $showLateCheckoutModal = false;
    public $requestedCheckoutTime = '';
    public $guestNotes = '';

    public function mount(HotelBooking $booking)
    {
        // Ensure user owns this booking
        if ($booking->guest_id !== auth()->id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        $this->booking = $booking->load(['hotel', 'room', 'lateCheckoutRequest']);
    }

    public function openLateCheckoutModal()
    {
        // Set default time to 2 hours after default checkout
        $defaultCheckout = \Carbon\Carbon::parse($this->booking->hotel->default_checkout_time);
        $this->requestedCheckoutTime = $defaultCheckout->addHours(2)->format('H:i');

        $this->guestNotes = '';
        $this->showLateCheckoutModal = true;
    }

    public function closeLateCheckoutModal()
    {
        $this->showLateCheckoutModal = false;
        $this->reset('requestedCheckoutTime', 'guestNotes');
    }

    public function submitLateCheckoutRequest()
    {
        // Validate
        $this->validate([
            'requestedCheckoutTime' => 'required',
            'guestNotes' => 'nullable|max:500',
        ]);

        try {
            $lateCheckoutService = app(LateCheckoutService::class);

            $result = $lateCheckoutService->createRequest(
                $this->booking,
                $this->requestedCheckoutTime . ':00',
                $this->guestNotes
            );

            if ($result['success']) {
                session()->flash('success', 'Late checkout request submitted successfully. You will be notified once the hotel manager reviews your request.');
                $this->closeLateCheckoutModal();

                // Refresh booking data
                $this->booking->refresh()->load(['hotel', 'room', 'lateCheckoutRequest']);
            } else {
                session()->flash('error', implode(' ', $result['errors']));
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function cancelLateCheckoutRequest()
    {
        try {
            if (!$this->booking->lateCheckoutRequest || !$this->booking->lateCheckoutRequest->isPending()) {
                session()->flash('error', 'No pending late checkout request found.');
                return;
            }

            $this->booking->lateCheckoutRequest->cancel();

            session()->flash('success', 'Late checkout request cancelled successfully.');

            // Refresh booking data
            $this->booking->refresh()->load(['hotel', 'room', 'lateCheckoutRequest']);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel request: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.visitor.booking.show')->layout('layouts.visitor');
    }
}
