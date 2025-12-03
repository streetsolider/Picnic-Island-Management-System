<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('layouts.staff')]
#[Title('Theme Park Settings')]
class Settings extends Component
{
    #[Validate('required|numeric|min:5|max:1000')]
    public $ticketPrice;

    public $currentPrice;

    public function mount()
    {
        $this->currentPrice = ThemeParkSetting::getTicketPrice();
        $this->ticketPrice = $this->currentPrice;
    }

    public function save()
    {
        $this->validate();

        ThemeParkSetting::setTicketPrice($this->ticketPrice, auth('staff')->id());

        $this->currentPrice = $this->ticketPrice;

        session()->flash('success', 'Ticket price updated successfully.');

        $this->dispatch('ticket-price-updated');
    }

    public function render()
    {
        return view('livewire.theme-park.settings');
    }
}
