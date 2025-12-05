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
            // Staff Dashboard: Activities in their assigned zone
            $staffZone = ThemeParkZone::where('assigned_staff_id', auth('staff')->id())->first();

            if ($staffZone) {
                $data['myZone'] = $staffZone;
                $data['myActivities'] = ThemeParkActivity::where('theme_park_zone_id', $staffZone->id)
                    ->with(['zone'])
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();

                $data['totalActivities'] = $data['myActivities']->count();
                $data['continuousRides'] = $data['myActivities']->where('activity_type', 'continuous')->count();
                $data['scheduledShows'] = $data['myActivities']->where('activity_type', 'scheduled')->count();
            } else {
                $data['myZone'] = null;
                $data['myActivities'] = collect([]);
                $data['totalActivities'] = 0;
                $data['continuousRides'] = 0;
                $data['scheduledShows'] = 0;
            }

            $data['creditPrice'] = ThemeParkSetting::getCreditPrice();
        }

        return view('livewire.theme-park.dashboard', $data);
    }
}
