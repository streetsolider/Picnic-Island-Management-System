<?php

namespace Database\Seeders;

use App\Models\ThemeParkZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeParkZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Note: Zones are managed by Theme Park Manager, not assigned to individual staff
     */
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Adventure Zone 1',
                'zone_type' => 'Adventure',
                'description' => 'High-energy activities and thrill rides for adventurous visitors.',
                'is_active' => true,
            ],
            [
                'name' => 'Water Park North',
                'zone_type' => 'Water Park',
                'description' => 'Exciting water slides, wave pools, and lazy rivers.',
                'is_active' => true,
            ],
            [
                'name' => 'Kids Kingdom Central',
                'zone_type' => 'Kids Area',
                'description' => 'Safe and fun activities designed for children under 12.',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment Plaza',
                'zone_type' => 'Entertainment',
                'description' => 'Live shows, performances, and interactive entertainment.',
                'is_active' => true,
            ],
            [
                'name' => 'Thrill Riders West',
                'zone_type' => 'Thrill Rides',
                'description' => 'Extreme rides for adrenaline junkies.',
                'is_active' => true,
            ],
            [
                'name' => 'Family Fun Zone East',
                'zone_type' => 'Family',
                'description' => 'Activities suitable for all ages and families.',
                'is_active' => true,
            ],
            [
                'name' => 'Adventure Zone 2',
                'zone_type' => 'Adventure',
                'description' => 'Another adventure zone with different activities.',
                'is_active' => true,
            ],
            [
                'name' => 'Water Park South',
                'zone_type' => 'Water Park',
                'description' => 'Additional water park facilities with tropical theme.',
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zoneData) {
            ThemeParkZone::create($zoneData);
        }
    }
}
