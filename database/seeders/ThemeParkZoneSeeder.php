<?php

namespace Database\Seeders;

use App\Models\ThemeParkZone;
use App\Models\Staff;
use App\Enums\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeParkZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get theme park staff if available
        $themeParkStaff = Staff::where('role', StaffRole::THEME_PARK_STAFF)
            ->where('is_active', true)
            ->get();

        $zones = [
            [
                'name' => 'Adventure Zone',
                'zone_type' => 'Adventure Zone',
                'description' => 'High-energy activities and thrill rides for adventurous visitors.',
                'capacity_limit' => 500,
                'opening_time' => '09:00',
                'closing_time' => '18:00',
                'is_active' => true,
            ],
            [
                'name' => 'Water World',
                'zone_type' => 'Water Park',
                'description' => 'Exciting water slides, wave pools, and lazy rivers.',
                'capacity_limit' => 800,
                'opening_time' => '10:00',
                'closing_time' => '19:00',
                'is_active' => true,
            ],
            [
                'name' => 'Kids Kingdom',
                'zone_type' => 'Kids Area',
                'description' => 'Safe and fun activities designed for children under 12.',
                'capacity_limit' => 300,
                'opening_time' => '09:00',
                'closing_time' => '17:00',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment Plaza',
                'zone_type' => 'Entertainment Zone',
                'description' => 'Live shows, performances, and interactive entertainment.',
                'capacity_limit' => 1000,
                'opening_time' => '11:00',
                'closing_time' => '20:00',
                'is_active' => true,
            ],
            [
                'name' => 'Thrill Seekers',
                'zone_type' => 'Thrill Rides',
                'description' => 'Extreme rides for adrenaline junkies.',
                'capacity_limit' => 400,
                'opening_time' => '10:00',
                'closing_time' => '18:00',
                'is_active' => true,
            ],
            [
                'name' => 'Family Fun Zone',
                'zone_type' => 'Family Zone',
                'description' => 'Activities suitable for all ages and families.',
                'capacity_limit' => 600,
                'opening_time' => '09:00',
                'closing_time' => '19:00',
                'is_active' => true,
            ],
        ];

        foreach ($zones as $index => $zoneData) {
            // Assign staff if available
            if ($themeParkStaff->isNotEmpty()) {
                $zoneData['assigned_staff_id'] = $themeParkStaff[$index % $themeParkStaff->count()]->id;
            }

            ThemeParkZone::create($zoneData);
        }
    }
}
