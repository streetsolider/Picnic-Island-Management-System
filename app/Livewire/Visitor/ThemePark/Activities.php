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
    public $selectedSchedule = null;
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
        $this->selectedActivity = ThemeParkActivity::with(['zone', 'showSchedules' => function ($q) {
            $q->where('status', 'scheduled')
                ->whereRaw('tickets_sold < venue_capacity')
                ->where(function ($query) {
                    // Future dates
                    $query->where('show_date', '>', now()->toDateString())
                        // OR today but not started yet
                        ->orWhere(function ($q) {
                            $q->where('show_date', '=', now()->toDateString())
                                ->whereRaw("TIME(show_time) > TIME(?)", [now()->toTimeString()]);
                        });
                })
                ->orderBy('show_date', 'asc')
                ->orderBy('show_time', 'asc');
        }])->find($activityId);
        $this->numberOfPersons = 1; // Reset to 1 when selecting new activity
        $this->selectedSchedule = null; // Reset schedule selection
    }

    public function cancelRedemption()
    {
        $this->selectedActivity = null;
        $this->selectedSchedule = null;
        $this->numberOfPersons = 1; // Reset when canceling
    }

    public function purchaseTicket()
    {
        \Log::info('Purchase ticket method called', [
            'selectedActivity' => $this->selectedActivity?->id,
            'numberOfPersons' => $this->numberOfPersons,
            'selectedSchedule' => $this->selectedSchedule,
        ]);

        if (!$this->selectedActivity) {
            session()->flash('error', 'Please select an activity first.');
            return;
        }

        // Validate number of persons
        $this->validate([
            'numberOfPersons' => 'required|integer|min:1|max:50',
        ]);

        $service = app(ThemeParkTicketService::class);

        // Check if activity is continuous or scheduled
        if ($this->selectedActivity->isContinuous()) {
            // Continuous ride - no schedule needed
            \Log::info('Calling purchaseContinuousRideTicket');
            $result = $service->purchaseContinuousRideTicket(
                auth()->id(),
                $this->selectedActivity->id,
                $this->numberOfPersons
            );
            \Log::info('Result from purchaseContinuousRideTicket', $result);
        } else {
            // Scheduled show - validate schedule selection
            if (!$this->selectedSchedule) {
                session()->flash('error', 'Please select a show schedule.');
                return;
            }

            \Log::info('Calling purchaseShowTicket', [
                'userId' => auth()->id(),
                'activityId' => $this->selectedActivity->id,
                'scheduleId' => $this->selectedSchedule,
                'quantity' => $this->numberOfPersons,
            ]);

            $result = $service->purchaseShowTicket(
                auth()->id(),
                $this->selectedActivity->id,
                $this->selectedSchedule,
                $this->numberOfPersons
            );

            \Log::info('Result from purchaseShowTicket', $result);
        }

        if ($result['success']) {
            $ticketRef = $result['ticket']->ticket_reference ?? 'N/A';
            session()->flash('success', $result['message'] . ' Your ticket reference is: <strong>' . $ticketRef . '</strong>');
            $this->selectedActivity = null;
            $this->selectedSchedule = null;
            $this->numberOfPersons = 1;
            $this->loadWallet(); // Refresh wallet

            // Redirect to refresh the page and show flash message
            return $this->redirect(route('visitor.theme-park.activities'), navigate: true);
        } else {
            \Log::error('Purchase failed', ['error' => $result['message']]);
            session()->flash('error', $result['message']);
        }
    }

    public function render()
    {
        $zones = ThemeParkZone::with('activities')->get();

        $query = ThemeParkActivity::with(['zone', 'showSchedules' => function ($q) {
                // Only load future schedules with available seats
                $q->where('status', 'scheduled')
                    ->whereRaw('tickets_sold < venue_capacity')
                    ->where(function ($query) {
                        // Show schedules where date is in the future
                        $query->where('show_date', '>', now()->toDateString())
                            // OR date is today but show hasn't started yet
                            ->orWhere(function ($q) {
                                $q->where('show_date', '=', now()->toDateString())
                                    ->whereRaw("TIME(show_time) > TIME(?)", [now()->toTimeString()]);
                            });
                    })
                    ->orderBy('show_date', 'asc')
                    ->orderBy('show_time', 'asc');
            }])
            ->where('is_active', true)
            ->where(function ($query) {
                // Show ALL continuous rides (regardless of operating hours)
                $query->where('activity_type', 'continuous')
                    // OR scheduled shows that have at least one future schedule
                    ->orWhereHas('showSchedules', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'scheduled')
                            ->whereRaw('tickets_sold < venue_capacity')
                            ->where(function ($timeQuery) {
                                // Future dates
                                $timeQuery->where('show_date', '>', now()->toDateString())
                                    // OR today but not started yet
                                    ->orWhere(function ($todayQuery) {
                                        $todayQuery->where('show_date', '=', now()->toDateString())
                                            ->whereRaw("TIME(show_time) > TIME(?)", [now()->toTimeString()]);
                                    });
                            });
                    });
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
