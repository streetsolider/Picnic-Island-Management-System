<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkZone;
use App\Services\ThemeParkTicketService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Theme Park Dashboard')]
class Dashboard extends Component
{
    public $zone;
    public $stats = [];
    public $recentRedemptions = [];

    public function mount()
    {
        // Get the zone assigned to this staff member
        $this->zone = ThemeParkZone::where('assigned_staff_id', auth()->id())
            ->with(['activities'])
            ->first();

        if (!$this->zone) {
            session()->flash('error', 'No zone assigned to you.');
            return;
        }

        $this->loadStats();
        $this->loadRecentRedemptions();
    }

    public function loadStats()
    {
        if (!$this->zone) {
            return;
        }

        $service = app(ThemeParkTicketService::class);
        $this->stats = $service->getStaffStats($this->zone->id);
    }

    public function loadRecentRedemptions()
    {
        if (!$this->zone) {
            return;
        }

        $service = app(ThemeParkTicketService::class);
        $this->recentRedemptions = $service->getRecentRedemptions($this->zone->id, 5);
    }

    public function render()
    {
        return view('livewire.theme-park.dashboard');
    }
}
