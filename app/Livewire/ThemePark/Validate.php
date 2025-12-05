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

    public $ticket = null;
    public $validationResult = null;
    public $searchPerformed = false;

    public function validateTicket()
    {
        $this->validate([
            'qrCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);
        $result = $service->validateAndRedeemTicket($this->qrCode, auth('staff')->id());

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
            if (isset($result['ticket'])) {
                $this->ticket = $result['ticket'];
            }
        }

        $this->qrCode = '';
    }

    public function checkStatus()
    {
        $this->validate([
            'qrCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);
        $result = $service->checkTicketStatus($this->qrCode);

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            session()->flash('info', 'Ticket status retrieved (not redeemed).');
        } else {
            session()->flash('error', $result['message']);
        }

        $this->qrCode = '';
    }

    public function resetSearch()
    {
        $this->reset(['qrCode', 'ticket', 'validationResult', 'searchPerformed']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.theme-park.validate');
    }
}
