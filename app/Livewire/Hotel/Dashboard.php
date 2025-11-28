<?php

namespace App\Livewire\Hotel;

use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $hotels;

    public function mount()
    {
        // Get all hotels managed by the current user
        $this->hotels = Hotel::where('manager_id', auth('staff')->user()->id)
            ->withCount(['rooms', 'amenities'])
            ->get();

        if ($this->hotels->isEmpty()) {
            abort(403, 'You are not assigned to manage any hotel.');
        }
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.dashboard');
    }
}
