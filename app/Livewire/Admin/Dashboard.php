<?php

namespace App\Livewire\Admin;

use App\Models\Staff;
use App\Models\Guest;
use App\Enums\StaffRole;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        // Load statistics for admin dashboard
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_staff' => Staff::count(),
            'total_guests' => Guest::count(),
            'hotel_managers' => Staff::where('role', StaffRole::HOTEL_MANAGER)->count(),
            'ferry_operators' => Staff::where('role', StaffRole::FERRY_OPERATOR)->count(),
            'theme_park_staff' => Staff::where('role', StaffRole::THEME_PARK_STAFF)->count(),
            'administrators' => Staff::where('role', StaffRole::ADMINISTRATOR)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
