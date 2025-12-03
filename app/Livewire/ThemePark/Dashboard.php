<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivitySchedule;
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
            $data['unassignedActivities'] = ThemeParkActivity::whereNull('assigned_staff_id')->count();
            $data['ticketPrice'] = ThemeParkSetting::getTicketPrice();

            // Recent activities
            $data['recentActivities'] = ThemeParkActivity::with(['zone', 'assignedStaff'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Zones list
            $data['zones'] = ThemeParkZone::withCount('activities')
                ->orderBy('name')
                ->get();
        } else {
            // Staff Dashboard: Their assigned activities and schedules
            $data['myActivities'] = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
                ->with(['zone'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $data['totalActivities'] = $data['myActivities']->count();

            // Today's schedules
            $data['todaySchedules'] = ThemeParkActivitySchedule::with(['activity.zone'])
                ->whereHas('activity', function ($q) {
                    $q->where('assigned_staff_id', auth('staff')->id());
                })
                ->whereDate('schedule_date', today())
                ->orderBy('start_time')
                ->get();

            // Upcoming schedules (next 7 days)
            $data['upcomingSchedules'] = ThemeParkActivitySchedule::with(['activity.zone'])
                ->whereHas('activity', function ($q) {
                    $q->where('assigned_staff_id', auth('staff')->id());
                })
                ->whereBetween('schedule_date', [today()->addDay(), today()->addDays(7)])
                ->orderBy('schedule_date')
                ->orderBy('start_time')
                ->limit(10)
                ->get();

            $data['ticketPrice'] = ThemeParkSetting::getTicketPrice();
        }

        return view('livewire.theme-park.dashboard', $data);
    }
}
