<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkZone;
use App\Models\ThemeParkSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Theme Park Dashboard')]
class Dashboard extends Component
{
    public $isManager = false;

    public function mount()
    {
        $this->isManager = auth('staff')->user()->role->value === 'theme_park_manager';
    }

    public function render()
    {
        $data = [];

        if ($this->isManager) {
            // Manager Dashboard: Overview of all zones and activities
            $data['totalZones'] = ThemeParkZone::count();
            $data['activeZones'] = ThemeParkZone::where('is_active', true)->count();
            $data['totalActivities'] = ThemeParkActivity::count();
            $data['activeActivities'] = ThemeParkActivity::where('is_active', true)->count();
            $data['creditPrice'] = ThemeParkSetting::getCreditPrice();

            // Recent activities
            $data['recentActivities'] = ThemeParkActivity::with(['zone'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Zones list
            $data['zones'] = ThemeParkZone::withCount('activities')
                ->orderBy('name')
                ->get();
        } else {
            // Staff Dashboard: Activities assigned to this staff member
            $data['myActivities'] = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
                ->with(['zone'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $data['totalActivities'] = $data['myActivities']->count();
            $data['continuousRides'] = $data['myActivities']->where('activity_type', 'continuous')->count();
            $data['scheduledShows'] = $data['myActivities']->where('activity_type', 'scheduled')->count();

            // Get unique zones from assigned activities
            $data['myZones'] = $data['myActivities']->pluck('zone')->unique('id');

            // Get today's schedules for assigned scheduled shows
            $scheduledActivityIds = $data['myActivities']
                ->where('activity_type', 'scheduled')
                ->pluck('id');

            $data['todaySchedules'] = \App\Models\ThemeParkShowSchedule::whereIn('activity_id', $scheduledActivityIds)
                ->where('show_date', today())
                ->where('status', 'scheduled')
                ->with('activity')
                ->orderBy('show_time')
                ->get();

            // Get upcoming schedules (next 7 days)
            $data['upcomingSchedules'] = \App\Models\ThemeParkShowSchedule::whereIn('activity_id', $scheduledActivityIds)
                ->where('show_date', '>', today())
                ->where('show_date', '<=', today()->addDays(7))
                ->where('status', 'scheduled')
                ->with('activity')
                ->orderBy('show_date')
                ->orderBy('show_time')
                ->limit(10)
                ->get();

            $data['creditPrice'] = ThemeParkSetting::getCreditPrice();
        }

        return view('livewire.theme-park.dashboard', $data);
    }
}
