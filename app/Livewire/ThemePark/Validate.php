<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkZone;
use App\Services\ThemeParkTicketService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate as ValidateAttribute;

#[Layout('layouts.staff')]
#[Title('Validate Tickets')]
class Validate extends Component
{
    public $zone;

    #[ValidateAttribute('required|string|min:8')]
    public $searchCode = '';

    public $redemption = null;
    public $searchPerformed = false;

    public function mount()
    {
        $this->zone = ThemeParkZone::where('assigned_staff_id', auth()->id())->first();

        if (!$this->zone) {
            session()->flash('error', 'No zone assigned to you.');
        }
    }

    public function searchRedemption()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->redemption = null;

        $service = app(ThemeParkTicketService::class);
        $result = $service->validateRedemption($this->searchCode, auth()->id());

        if ($result['success']) {
            $this->redemption = $result['redemption'];
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
            if (isset($result['redemption'])) {
                $this->redemption = $result['redemption'];
            }
        }

        $this->searchCode = '';
    }

    public function resetSearch()
    {
        $this->reset(['searchCode', 'redemption', 'searchPerformed']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.theme-park.validate');
    }
}
