<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkZone;
use App\Models\ThemeParkWallet;
use App\Services\ThemeParkTicketService;
use Livewire\Component;
use Livewire\WithPagination;

class Activities extends Component
{
    use WithPagination;

    public $selectedZone = null;
    public $wallet;
    public $selectedActivity = null;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadWallet();
    }

    public function loadWallet()
    {
        $this->wallet = ThemeParkWallet::getOrCreateForUser(auth()->id());
    }

    public function selectActivity($activityId)
    {
        $this->selectedActivity = ThemeParkActivity::with('zone')->find($activityId);
    }

    public function redeemTickets()
    {
        if (!$this->selectedActivity) {
            session()->flash('error', 'Please select an activity first.');
            return;
        }

        $service = app(ThemeParkTicketService::class);
        $result = $service->redeemTickets(auth()->id(), $this->selectedActivity->id);

        if ($result['success']) {
            session()->flash('success', $result['message'] . ' Your redemption code is: ' . $result['redemption']->redemption_reference);
            $this->loadWallet();
            $this->selectedActivity = null;
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function render()
    {
        $zones = ThemeParkZone::with('activities')->get();

        $query = ThemeParkActivity::with('zone')
            ->where('is_active', true);

        if ($this->selectedZone) {
            $query->where('theme_park_zone_id', $this->selectedZone);
        }

        $activities = $query->orderBy('name')->paginate(12);

        return view('livewire.visitor.theme-park.activities', [
            'zones' => $zones,
            'activities' => $activities,
        ])->layout('layouts.visitor');
    }
}
