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
    public $creditPrice;

    public $currentPrice;

    public function mount()
    {
        $this->currentPrice = ThemeParkSetting::getCreditPrice();
        $this->creditPrice = $this->currentPrice;
    }

    public function save()
    {
        $this->validate();

        ThemeParkSetting::setCreditPrice($this->creditPrice, auth('staff')->id());

        $this->currentPrice = $this->creditPrice;

        session()->flash('success', 'Credit price updated successfully.');

        $this->dispatch('credit-price-updated');
    }

    public function render()
    {
        return view('livewire.theme-park.settings');
    }
}
