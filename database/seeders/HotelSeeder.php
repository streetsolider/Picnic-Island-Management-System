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
                'name' => 'Paradise Bay Resort',
                'location' => 'North Beach, Picnic Island',
                'description' => 'A luxurious beachfront resort with stunning ocean views and world-class amenities.',
                'star_rating' => 5,
                'policies' => "Check-in: 3:00 PM\nCheck-out: 11:00 AM\nPets not allowed\nNo smoking in rooms",
                'is_active' => true,
            ],
            [
                'name' => 'Island View Hotel',
                'location' => 'Central District, Picnic Island',
                'description' => 'Modern hotel in the heart of the island with easy access to all attractions.',
                'star_rating' => 4,
                'policies' => "Check-in: 2:00 PM\nCheck-out: 12:00 PM\nPets allowed with deposit\nFree WiFi",
                'is_active' => true,
            ],
            [
                'name' => 'Sunset Inn',
                'location' => 'West Coast, Picnic Island',
                'description' => 'Cozy boutique hotel perfect for couples and small families.',
                'star_rating' => 3,
                'policies' => "Check-in: 2:00 PM\nCheck-out: 11:00 AM\nBreakfast included",
                'is_active' => true,
            ],
            [
                'name' => 'Picnic Grand Hotel',
                'location' => 'Downtown, Picnic Island',
                'description' => 'Premium hotel offering luxury accommodations and exceptional service.',
                'star_rating' => 5,
                'policies' => "Check-in: 3:00 PM\nCheck-out: 12:00 PM\n24-hour concierge\nValet parking available",
                'is_active' => true,
            ],
            [
                'name' => 'Budget Stay Lodge',
                'location' => 'South End, Picnic Island',
                'description' => 'Affordable accommodation for budget-conscious travelers.',
                'star_rating' => 2,
                'policies' => "Check-in: 1:00 PM\nCheck-out: 10:00 AM\nSelf-service check-in available",
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
