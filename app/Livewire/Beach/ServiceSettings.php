<?php

namespace App\Livewire\Beach;

use App\Models\BeachService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Service Settings')]
class ServiceSettings extends Component
{
    public $service;
    public $opening_time;
    public $closing_time;
    public $slot_price;
    public $price_per_hour;
    public $concurrent_capacity;
    public $is_active;

    public function mount()
    {
        $staffId = auth('staff')->id();

        // Get the selected service from session
        $selectedServiceId = session('beach_selected_service_id');

        // Get the beach service
        $this->service = $selectedServiceId
            ? BeachService::where('id', $selectedServiceId)
                ->where('assigned_staff_id', $staffId)
                ->with('category')
                ->first()
            : BeachService::where('assigned_staff_id', $staffId)
                ->with('category')
                ->first();

        if ($this->service) {
            $this->opening_time = $this->service->opening_time ? substr($this->service->opening_time, 0, 5) : null;
            $this->closing_time = $this->service->closing_time ? substr($this->service->closing_time, 0, 5) : null;
            $this->slot_price = $this->service->slot_price;
            $this->price_per_hour = $this->service->price_per_hour;
            $this->concurrent_capacity = $this->service->concurrent_capacity;
            $this->is_active = $this->service->is_active;
        }
    }

    public function rules()
    {
        $rules = [
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'concurrent_capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];

        // Conditional validation based on booking type
        if ($this->service && $this->service->booking_type === 'fixed_slot') {
            $rules['slot_price'] = 'required|numeric|min:0';
        } else {
            $rules['price_per_hour'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function saveSettings()
    {
        if (!$this->service) {
            session()->flash('error', 'No service assigned to you.');
            return;
        }

        $this->validate();

        $updateData = [
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'concurrent_capacity' => $this->concurrent_capacity,
            'is_active' => $this->is_active,
        ];

        // Add pricing based on booking type
        if ($this->service->booking_type === 'fixed_slot') {
            $updateData['slot_price'] = $this->slot_price;
        } else {
            $updateData['price_per_hour'] = $this->price_per_hour;
        }

        $this->service->update($updateData);

        session()->flash('success', 'Service settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.beach.service-settings');
    }
}
