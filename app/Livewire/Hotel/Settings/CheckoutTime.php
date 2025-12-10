<?php

namespace App\Livewire\Hotel\Settings;

use App\Models\Hotel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CheckoutTime extends Component
{
    public Hotel $hotel;
    public $showEditModal = false;
    public $checkoutTime;

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)
            ->firstOrFail();

        $this->checkoutTime = $this->hotel->default_checkout_time
            ? Carbon::parse($this->hotel->default_checkout_time)->format('H:i')
            : '12:00';
    }

    public function openEditModal()
    {
        $this->checkoutTime = $this->hotel->default_checkout_time
            ? Carbon::parse($this->hotel->default_checkout_time)->format('H:i')
            : '12:00';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset('checkoutTime');
    }

    public function save()
    {
        // Validate checkout time is between 10:00 AM and 2:00 PM
        $this->validate([
            'checkoutTime' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
                function ($attribute, $value, $fail) {
                    $time = Carbon::parse($value);
                    $minTime = Carbon::parse('10:00');
                    $maxTime = Carbon::parse('14:00');

                    if ($time->lessThan($minTime) || $time->greaterThan($maxTime)) {
                        $fail('Checkout time must be between 10:00 AM and 2:00 PM.');
                    }
                },
            ],
        ]);

        try {
            // Update hotel checkout time
            $this->hotel->default_checkout_time = $this->checkoutTime . ':00';
            $this->hotel->save();

            $this->closeEditModal();

            session()->flash('message', 'Default checkout time updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update checkout time: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.settings.checkout-time');
    }
}
