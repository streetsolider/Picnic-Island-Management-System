<?php

namespace App\Livewire\Beach;

use App\Models\BeachService;
use App\Services\BeachValidationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Validate Beach Bookings')]
class Validate extends Component
{
    public $searchCode = '';
    public $booking = null;
    public $validationResult = null;
    public $searchPerformed = false;

    public function searchRedemption()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->booking = null;
        $this->validationResult = null;

        $service = app(BeachValidationService::class);

        // First, check booking status without redeeming
        $checkResult = $service->checkBookingStatus($this->searchCode);

        if (!$checkResult['success']) {
            session()->flash('error', $checkResult['message']);
            return;
        }

        // Now redeem the booking
        $result = $service->validateAndRedeemBooking($this->searchCode, auth('staff')->id());

        $this->validationResult = $result;

        if ($result['success']) {
            $this->booking = $result['booking'];
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
            if (isset($result['booking'])) {
                $this->booking = $result['booking'];
            }
        }

        $this->searchCode = '';
    }

    public function validateBooking()
    {
        // Alias for searchRedemption
        return $this->searchRedemption();
    }

    public function checkStatus()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->booking = null;
        $this->validationResult = null;

        $service = app(BeachValidationService::class);
        $result = $service->checkBookingStatus($this->searchCode);

        $this->validationResult = $result;

        if ($result['success']) {
            $this->booking = $result['booking'];
            session()->flash('info', 'Booking status retrieved (not redeemed).');
        } else {
            session()->flash('error', $result['message']);
        }

        $this->searchCode = '';
    }

    public function resetSearch()
    {
        $this->reset(['searchCode', 'booking', 'validationResult', 'searchPerformed']);
        $this->resetValidation();
    }

    public function render()
    {
        $staffId = auth('staff')->id();

        // Get the selected service from session
        $selectedServiceId = session('beach_selected_service_id');

        // Get staff's assigned beach service
        $assignedService = $selectedServiceId
            ? BeachService::where('id', $selectedServiceId)
                ->where('assigned_staff_id', $staffId)
                ->with('category')
                ->first()
            : BeachService::where('assigned_staff_id', $staffId)
                ->with('category')
                ->first();

        return view('livewire.beach.validate', [
            'assignedService' => $assignedService,
        ]);
    }
}
