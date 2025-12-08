<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Staff;
use App\Enums\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some hotel managers if available
        $hotelManagers = Staff::where('role', StaffRole::HOTEL_MANAGER)
            ->where('is_active', true)
            ->get();

        $hotels = [
            [
                'name' => 'Paradise Bay Hotel',
                'description' => 'A luxurious beachfront resort with stunning ocean views and world-class amenities.',
                'star_rating' => 5,
                'room_capacity' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Island View Hotel',
                'description' => 'Modern hotel in the heart of the island with easy access to all attractions.',
                'star_rating' => 4,
                'room_capacity' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Sunset Inn',
                'description' => 'Cozy boutique hotel perfect for couples and small families.',
                'star_rating' => 3,
                'room_capacity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Picnic Grand Hotel',
                'description' => 'Premium hotel offering luxury accommodations and exceptional service.',
                'star_rating' => 5,
                'room_capacity' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Budget Stay Lodge',
                'description' => 'Affordable accommodation for budget-conscious travelers.',
                'star_rating' => 2,
                'room_capacity' => 30,
                'is_active' => false,
            ],
        ];

        foreach ($hotels as $index => $hotelData) {
            // Assign a manager if available
            if ($hotelManagers->isNotEmpty()) {
                $hotelData['manager_id'] = $hotelManagers[$index % $hotelManagers->count()]->id;
            }

            Hotel::create($hotelData);
        }
    }
}
