<?php

namespace Database\Seeders;

use App\Models\ThemeParkZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeParkZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Adventure Zone',
                'zone_type' => 'Adventure',
                'description' => 'High-energy activities and outdoor adventures for thrill seekers.',
                'opening_time' => '09:00:00',
                'closing_time' => '21:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Water Park',
                'zone_type' => 'Water Park',
                'description' => 'Exciting water slides, wave pools, and aquatic attractions.',
                'opening_time' => '09:00:00',
                'closing_time' => '21:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment Zone',
                'zone_type' => 'Entertainment',
                'description' => 'Live shows, performances, and interactive entertainment.',
                'opening_time' => '09:00:00',
                'closing_time' => '21:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Ride Zone',
                'zone_type' => 'Rides',
                'description' => 'Thrilling rides and attractions for all ages.',
                'opening_time' => '09:00:00',
                'closing_time' => '21:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zoneData) {
            ThemeParkZone::create($zoneData);
        }

        $this->command->info('✓ Theme park zones seeded');
    }
}
