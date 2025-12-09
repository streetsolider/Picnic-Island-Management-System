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
                'name' => 'Theme Park Manager',
                'email' => 'themepark-manager@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::THEME_PARK_MANAGER,
            ],
            [
                'name' => 'Theme Park Staff',
                'email' => 'themepark-staff@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::THEME_PARK_STAFF,
            ],
            [
                'name' => 'Beach Staff',
                'email' => 'beach-staff@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => StaffRole::BEACH_STAFF,
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

        // Seed hotels
        $this->call(\Database\Seeders\Hotel\HotelSeeder::class);

        // Seed hotel policies
        $this->call(\Database\Seeders\Hotel\HotelPolicySeeder::class);

        // Seed hotel amenities
        $this->call(\Database\Seeders\Hotel\AmenitySeeder::class);

        // Seed rooms and pricing for all hotels
        $this->call(\Database\Seeders\Hotel\RoomSeeder::class);

        // Seed promotional discounts
        $this->call(\Database\Seeders\Hotel\PromotionalDiscountSeeder::class);

        // Seed ferry vessels
        $this->call(FerryVesselSeeder::class);

        // Seed theme park zones
        $this->call(ThemeParkZoneSeeder::class);

        // Seed beach services and categories
        $this->call(BeachActivityCategorySeeder::class);

        // Seed test data (bookings, activities, schedules)
        // TODO: Fix TestDataSeeder - currently creates unwanted test bookings and has errors
        // $this->call(TestDataSeeder::class);

        $this->command->info('Sample data created successfully!');
        $this->command->info('');
        $this->command->info('STAFF ACCOUNTS:');
        $this->command->info('Email: admin@picnicisland.com | Password: password | Role: Administrator');
        $this->command->info('Email: hotel@picnicisland.com | Password: password | Role: Hotel Manager');
        $this->command->info('Email: ferry@picnicisland.com | Password: password | Role: Ferry Operator');
        $this->command->info('Email: themepark-manager@picnicisland.com | Password: password | Role: Theme Park Manager');
        $this->command->info('Email: themepark-staff@picnicisland.com | Password: password | Role: Theme Park Staff');
        $this->command->info('Email: beach-staff@picnicisland.com | Password: password | Role: Beach Staff');
        $this->command->info('');
        $this->command->info('GUEST ACCOUNTS:');
        $this->command->info('Email: guest@example.com | Password: password');
        $this->command->info('Email: customer@example.com | Password: password');
    }
}
