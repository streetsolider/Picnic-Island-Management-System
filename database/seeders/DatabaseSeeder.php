<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
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
        // Create sample users for each role
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMINISTRATOR,
            ],
            [
                'name' => 'Hotel Manager',
                'email' => 'hotel@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => UserRole::HOTEL_MANAGER,
            ],
            [
                'name' => 'Ferry Operator',
                'email' => 'ferry@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => UserRole::FERRY_OPERATOR,
            ],
            [
                'name' => 'Theme Park Staff',
                'email' => 'themepark@picnicisland.com',
                'password' => Hash::make('password'),
                'role' => UserRole::THEME_PARK_STAFF,
            ],
            [
                'name' => 'John Visitor',
                'email' => 'visitor@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::VISITOR,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('Email: admin@picnicisland.com | Password: password');
        $this->command->info('Email: hotel@picnicisland.com | Password: password');
        $this->command->info('Email: ferry@picnicisland.com | Password: password');
        $this->command->info('Email: themepark@picnicisland.com | Password: password');
        $this->command->info('Email: visitor@example.com | Password: password');
    }
}
