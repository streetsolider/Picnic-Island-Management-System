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
    public $numberOfPersons = 1;

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
        $this->numberOfPersons = 1; // Reset to 1 when selecting new activity
    }

    public function cancelRedemption()
    {
        $this->selectedActivity = null;
        $this->numberOfPersons = 1; // Reset when canceling
    }

    public function redeemTickets()
    {
        if (!$this->selectedActivity) {
            session()->flash('error', 'Please select an activity first.');
            return;
        }

        // Validate number of persons
        $this->validate([
            'numberOfPersons' => 'required|integer|min:1|max:50',
        ]);

        $service = app(ThemeParkTicketService::class);
        $result = $service->redeemTickets(auth()->id(), $this->selectedActivity->id, $this->numberOfPersons);

        if ($result['success']) {
            session()->flash('success', $result['message'] . ' Your redemption code is: <strong>' . $result['redemption']->redemption_reference . '</strong>');
            $this->selectedActivity = null;
            $this->numberOfPersons = 1;

            // Redirect to refresh the page and show flash message
            return $this->redirect(route('visitor.theme-park.activities'), navigate: true);
        } else {
            session()->flash('error', $result['message']);
            $this->selectedActivity = null;
        }
    }

    public function render()
    {
        $zones = ThemeParkZone::with('activities')->get();

        $query = ThemeParkActivity::with(['zone', 'showSchedules' => function ($q) {
                // Only load future schedules with available seats
                $q->where('show_date', '>=', now()->toDateString())
                    ->whereRaw('tickets_sold < venue_capacity')
                    ->where('status', 'scheduled')
                    ->orderBy('show_date', 'asc')
                    ->orderBy('show_time', 'asc');
            }])
            ->where('is_active', true)
            // Only show activities that have at least one active schedule
            ->whereHas('showSchedules', function ($q) {
                $q->where('show_date', '>=', now()->toDateString())
                    ->whereRaw('tickets_sold < venue_capacity')
                    ->where('status', 'scheduled');
            });

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
