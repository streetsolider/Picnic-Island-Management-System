<?php

namespace Database\Seeders;

use App\Enums\StaffRole;
use App\Models\Staff;
use App\Models\Guest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample staff members
        $staffMembers = [
            [
                'name' => 'Admin User',
                'email' => 'admin@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::ADMINISTRATOR,
            ],
            [
                'name' => 'Hotel Manager',
                'email' => 'hotel@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::HOTEL_MANAGER,
            ],
            [
                'name' => 'Ferry Operator',
                'email' => 'ferry@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::FERRY_OPERATOR,
            ],
            [
                'name' => 'Theme Park Staff',
                'email' => 'themepark@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::THEME_PARK_STAFF,
            ],
        ];

        foreach ($staffMembers as $staffData) {
            // Generate staff_id before creating
            $staffData['staff_id'] = Staff::generateStaffId();
            Staff::create($staffData);
        }

        // Create sample guests
        $guests = [
            [
                'name' => 'John Visitor',
                'email' => 'guest@example.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
            ],
            [
                'name' => 'Jane Customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'phone' => '+1987654321',
            ],
        ];

        foreach ($guests as $guestData) {
            // Generate guest_id before creating
            $guestData['guest_id'] = Guest::generateGuestId();
            Guest::create($guestData);
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('');
        $this->command->info('STAFF ACCOUNTS:');
        $this->command->info('Email: admin@picnicisland.com | Password: password | Role: Administrator');
        $this->command->info('Email: hotel@picnicisland.com | Password: password | Role: Hotel Manager');
        $this->command->info('Email: ferry@picnicisland.com | Password: password | Role: Ferry Operator');
        $this->command->info('Email: themepark@picnicisland.com | Password: password | Role: Theme Park Staff');
        $this->command->info('');
        $this->command->info('GUEST ACCOUNTS:');
        $this->command->info('Email: guest@example.com | Password: password');
        $this->command->info('Email: customer@example.com | Password: password');
    }
}
