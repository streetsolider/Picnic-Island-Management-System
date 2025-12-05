<?php

namespace App\Livewire\ThemePark;

use App\Services\ThemeParkValidationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate as ValidateAttribute;

#[Layout('layouts.staff')]
#[Title('Validate Activity Tickets')]
class Validate extends Component
{
    #[ValidateAttribute('required|string|min:8')]
    public $qrCode = '';

    public $searchCode = ''; // For the search input

    public $ticket = null;
    public $redemption = null; // Alias for ticket (for view compatibility)
    public $validationResult = null;
    public $searchPerformed = false;

    public function searchRedemption()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->redemption = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);
        $result = $service->validateAndRedeemTicket($this->searchCode, auth('staff')->id());

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            $this->redemption = $result['ticket']; // Set redemption as alias
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
            if (isset($result['ticket'])) {
                $this->ticket = $result['ticket'];
                $this->redemption = $result['ticket']; // Set redemption as alias
            }
        }

        $this->searchCode = '';
    }

    public function validateTicket()
    {
        // Alias for searchRedemption for backward compatibility
        return $this->searchRedemption();
    }

    public function checkStatus()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->redemption = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);
        $result = $service->checkTicketStatus($this->searchCode);

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            $this->redemption = $result['ticket']; // Set redemption as alias
            session()->flash('info', 'Ticket status retrieved (not redeemed).');
        } else {
            session()->flash('error', $result['message']);
        }

        $this->searchCode = '';
    }

    public function resetSearch()
    {
        $this->reset(['qrCode', 'searchCode', 'ticket', 'redemption', 'validationResult', 'searchPerformed']);
        $this->resetValidation();
    }

    public function render()
    {
        // Check if staff has assigned activities
        $hasAssignedActivities = \App\Models\ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
            ->where('is_active', true)
            ->exists();

        return view('livewire.theme-park.validate', [
            'zone' => null, // No longer using zones
            'hasAssignedActivities' => $hasAssignedActivities,
        ]);
    }
}
