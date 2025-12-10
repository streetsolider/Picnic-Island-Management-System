<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkZone;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.visitor')]
#[Title('Theme Park Activities')]
class Browse extends Component
{
    use WithPagination;

    #[Url]
    public $selectedZone = null;

    #[Url]
    public $selectedActivity = null;

    public function bookActivity()
    {
        // Redirect to login if not authenticated
        if (!auth()->check()) {
            session()->flash('info', 'Please log in to book theme park activities.');
            return redirect()->route('login');
        }

        // Check if user has active hotel booking (checked-in status)
        $hasActiveBooking = \App\Models\HotelBooking::where('guest_id', auth()->id())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>=', now()->toDateString())
            ->exists();

        if (!$hasActiveBooking) {
            session()->flash('error', 'You need an active hotel booking to access the theme park. Please book a hotel room first.');
            return redirect()->route('booking.search');
        }

        // Redirect to authenticated activities page
        return redirect()->route('visitor.theme-park.activities');
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
                                    ->whereRaw("CONCAT(show_date, ' ', show_time) > ?", [now()->format('Y-m-d H:i:s')]);
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
                                            ->whereRaw("CONCAT(show_date, ' ', show_time) > ?", [now()->format('Y-m-d H:i:s')]);
                                    });
                            });
                    });
            });

        if ($this->selectedZone) {
            $query->where('theme_park_zone_id', $this->selectedZone);
        }

        // Handle specific activity from map (if passed in URL)
        if ($this->selectedActivity) {
            $query->where('id', $this->selectedActivity);
        }

        $activities = $query->orderBy('name')->paginate(12);

        return view('livewire.visitor.theme-park.browse', [
            'zones' => $zones,
            'activities' => $activities,
        ]);
    }
}
