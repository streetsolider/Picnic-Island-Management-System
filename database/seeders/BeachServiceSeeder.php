<?php

namespace Database\Seeders;

use App\Models\BeachService;
use App\Models\Staff;
use App\Enums\StaffRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BeachServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample beach staff members
        $beachStaff = [];

        $staffData = [
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::BEACH_STAFF,
                'is_active' => true,
            ],
            [
                'name' => 'James Rodriguez',
                'email' => 'james.rodriguez@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::BEACH_STAFF,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Kim',
                'email' => 'sarah.kim@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::BEACH_STAFF,
                'is_active' => true,
            ],
        ];

        foreach ($staffData as $data) {
            $beachStaff[] = Staff::create($data);
        }

        // Create sample beach services
        $beachServices = [
            [
                'name' => 'Island Excursions',
                'service_type' => 'Excursions',
                'description' => 'Guided tours around Picnic Island including nature trails, historical sites, and scenic viewpoints.',
                'assigned_staff_id' => $beachStaff[0]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Water Sports Center',
                'service_type' => 'Water Sports',
                'description' => 'Exciting water sports including jet skiing, banana boats, parasailing, and kayaking.',
                'assigned_staff_id' => $beachStaff[1]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Beach Sports Arena',
                'service_type' => 'Beach Sports',
                'description' => 'Beach volleyball, beach soccer, frisbee, and other fun beach sports activities.',
                'assigned_staff_id' => $beachStaff[2]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Beachside Huts',
                'service_type' => 'Beach Huts',
                'description' => 'Private beach huts for day stays with cooking facilities, BBQ grills, and beachfront access.',
                'assigned_staff_id' => $beachStaff[0]->id,
                'is_active' => true,
            ],
        ];

        foreach ($beachServices as $serviceData) {
            BeachService::create($serviceData);
        }
    }
}
