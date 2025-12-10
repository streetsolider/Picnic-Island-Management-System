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
    public $modalError = null; // Error messages for modal

    public $hotelBooking;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadWallet();
        $this->loadHotelBooking();
    }

    public function loadHotelBooking()
    {
        $this->hotelBooking = \App\Models\HotelBooking::where('guest_id', auth()->id())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>=', now()->toDateString())
            ->with(['hotel', 'lateCheckoutRequest'])
            ->first();
    }

    public function loadWallet()
    {
        $this->wallet = ThemeParkWallet::getOrCreateForUser(auth()->id());
    }

    public function selectActivity($activityId)
    {
        $checkoutDate = $this->hotelBooking ? $this->hotelBooking->check_out_date->toDateString() : null;
        $checkoutTime = $this->hotelBooking ? $this->hotelBooking->getEffectiveCheckoutTime()->format('H:i:s') : null;

        $this->selectedActivity = ThemeParkActivity::with(['zone', 'showSchedules' => function ($q) use ($checkoutDate, $checkoutTime) {
            $q->where('status', 'scheduled')
                ->whereRaw('tickets_sold < venue_capacity')
                ->where(function ($query) use ($checkoutDate, $checkoutTime) {
                    // Future dates
                    $query->where('show_date', '>', now()->toDateString())
                        // OR today but show hasn't started yet (compare full datetime)
                        ->orWhere(function ($q) {
                            $q->where('show_date', '=', now()->toDateString())
                                ->whereRaw("CONCAT(show_date, ' ', show_time) > ?", [now()->format('Y-m-d H:i:s')]);
                        });
                })
                // Exclude shows on checkout day that start at or after checkout time
                ->when($checkoutDate && $checkoutTime, function ($q) use ($checkoutDate, $checkoutTime) {
                    $q->where(function ($subQ) use ($checkoutDate, $checkoutTime) {
                        $subQ->where('show_date', '!=', $checkoutDate)
                             ->orWhereRaw("TIME(show_time) < TIME(?)", [$checkoutTime]);
                    });
                })
                ->orderBy('show_date', 'asc')
                ->orderBy('show_time', 'asc');
        }])->find($activityId);
        $this->numberOfPersons = 1; // Reset to 1 when selecting new activity
        $this->selectedSchedule = null; // Reset schedule selection
        $this->modalError = null; // Clear modal errors
    }

    public function cancelRedemption()
    {
        $this->selectedActivity = null;
        $this->selectedSchedule = null;
        $this->numberOfPersons = 1; // Reset when canceling
        $this->modalError = null; // Clear modal errors
    }

    public function purchaseTicket()
    {
        \Log::info('Purchase ticket method called', [
            'selectedActivity' => $this->selectedActivity?->id,
            'numberOfPersons' => $this->numberOfPersons,
            'selectedSchedule' => $this->selectedSchedule,
        ]);

        // Clear previous modal errors
        $this->modalError = null;

        if (!$this->selectedActivity) {
            $this->modalError = 'Please select an activity first.';
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
                $this->modalError = 'Please select a show schedule.';
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
            $this->modalError = null;
            $this->loadWallet(); // Refresh wallet

            // Redirect to refresh the page and show flash message
            return $this->redirect(route('visitor.theme-park.activities'), navigate: true);
        } else {
            \Log::error('Purchase failed', ['error' => $result['message']]);
            $this->modalError = $result['message'];
        }
    }

    public function render()
    {
        $zones = ThemeParkZone::with('activities')->get();
        $checkoutDate = $this->hotelBooking ? $this->hotelBooking->check_out_date->toDateString() : null;
        $checkoutTime = $this->hotelBooking ? $this->hotelBooking->getEffectiveCheckoutTime()->format('H:i:s') : null;

        $query = ThemeParkActivity::with(['zone', 'showSchedules' => function ($q) use ($checkoutDate, $checkoutTime) {
                // Only load future schedules with available seats
                $q->where('status', 'scheduled')
                    ->whereRaw('tickets_sold < venue_capacity')
                    ->where(function ($query) use ($checkoutDate, $checkoutTime) {
                        // Show schedules where date is in the future
                        $query->where('show_date', '>', now()->toDateString())
                            // OR date is today but show hasn't started yet (compare full datetime)
                            ->orWhere(function ($q) {
                                $q->where('show_date', '=', now()->toDateString())
                                    ->whereRaw("CONCAT(show_date, ' ', show_time) > ?", [now()->format('Y-m-d H:i:s')]);
                            });
                    })
                    // Exclude shows on checkout day that start at or after checkout time
                    ->when($checkoutDate && $checkoutTime, function ($q) use ($checkoutDate, $checkoutTime) {
                        $q->where(function ($subQ) use ($checkoutDate, $checkoutTime) {
                            $subQ->where('show_date', '!=', $checkoutDate)
                                 ->orWhereRaw("TIME(show_time) < TIME(?)", [$checkoutTime]);
                        });
                    })
                    ->orderBy('show_date', 'asc')
                    ->orderBy('show_time', 'asc');
            }])
            ->where('is_active', true)
            ->where(function ($query) use ($checkoutDate, $checkoutTime) {
                // Show ALL continuous rides (regardless of operating hours)
                $query->where('activity_type', 'continuous')
                    // OR scheduled shows that have at least one future schedule
                    ->orWhereHas('showSchedules', function ($scheduleQuery) use ($checkoutDate, $checkoutTime) {
                        $scheduleQuery->where('status', 'scheduled')
                            ->whereRaw('tickets_sold < venue_capacity')
                            ->where(function ($timeQuery) use ($checkoutDate, $checkoutTime) {
                                // Future dates
                                $timeQuery->where('show_date', '>', now()->toDateString())
                                    // OR today but not started yet (compare full datetime)
                                    ->orWhere(function ($todayQuery) {
                                        $todayQuery->where('show_date', '=', now()->toDateString())
                                            ->whereRaw("CONCAT(show_date, ' ', show_time) > ?", [now()->format('Y-m-d H:i:s')]);
                                    });
                            })
                            // Exclude shows on checkout day that start at or after checkout time
                            ->when($checkoutDate && $checkoutTime, function ($q) use ($checkoutDate, $checkoutTime) {
                                $q->where(function ($subQ) use ($checkoutDate, $checkoutTime) {
                                    $subQ->where('show_date', '!=', $checkoutDate)
                                         ->orWhereRaw("TIME(show_time) < TIME(?)", [$checkoutTime]);
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
