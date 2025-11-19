<?php

namespace Database\Seeders;

use App\Models\BeachArea;
use App\Models\Staff;
use App\Enums\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeachAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get theme park staff if available (they can also manage beaches)
        $themeParkStaff = Staff::where('role', StaffRole::THEME_PARK_STAFF)
            ->where('is_active', true)
            ->get();

        $beachAreas = [
            [
                'name' => 'North Beach',
                'location' => 'Northern Coast of Picnic Island',
                'description' => 'Calm waters perfect for families and swimming.',
                'capacity_limit' => 300,
                'opening_time' => '07:00',
                'closing_time' => '19:00',
                'is_active' => true,
            ],
            [
                'name' => 'South Beach',
                'location' => 'Southern Coast of Picnic Island',
                'description' => 'Popular spot for water sports and activities.',
                'capacity_limit' => 400,
                'opening_time' => '08:00',
                'closing_time' => '20:00',
                'is_active' => true,
            ],
            [
                'name' => 'Sunset Cove',
                'location' => 'West Coast of Picnic Island',
                'description' => 'Scenic beach area perfect for watching sunsets.',
                'capacity_limit' => 200,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
                'is_active' => true,
            ],
            [
                'name' => 'Adventure Bay',
                'location' => 'East Coast of Picnic Island',
                'description' => 'Ideal for kayaking, snorkeling, and diving.',
                'capacity_limit' => 250,
                'opening_time' => '08:00',
                'closing_time' => '18:00',
                'is_active' => true,
            ],
        ];

        foreach ($beachAreas as $index => $beachData) {
            // Assign staff if available
            if ($themeParkStaff->isNotEmpty()) {
                $beachData['assigned_staff_id'] = $themeParkStaff[$index % $themeParkStaff->count()]->id;
            }

            BeachArea::create($beachData);
        }
    }
}
