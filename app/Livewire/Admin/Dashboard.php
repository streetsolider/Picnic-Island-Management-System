<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Enums\UserRole;
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
            'total_users' => User::count(),
            'visitors' => User::where('role', UserRole::VISITOR)->count(),
            'hotel_managers' => User::where('role', UserRole::HOTEL_MANAGER)->count(),
            'ferry_operators' => User::where('role', UserRole::FERRY_OPERATOR)->count(),
            'theme_park_staff' => User::where('role', UserRole::THEME_PARK_STAFF)->count(),
            'administrators' => User::where('role', UserRole::ADMINISTRATOR)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
