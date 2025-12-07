<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeachServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // Get category IDs
        $waterSportsCategory = DB::table('beach_activity_categories')
            ->where('name', 'Water Sports')
            ->first();

        $beachActivitiesCategory = DB::table('beach_activity_categories')
            ->where('name', 'Beach Activities')
            ->first();

        // Beach Activities (Fixed Slots)
        $beachActivities = [
            [
                'beach_activity_category_id' => $beachActivitiesCategory->id,
                'name' => 'Beach Volleyball Court',
                'service_type' => 'Beach Sports',
                'description' => 'Professional beach volleyball court with net and equipment provided',
                'booking_type' => 'fixed_slot',
                'slot_duration_minutes' => 60, // 1-hour slots
                'slot_price' => 150.00, // MVR 150 per hour
                'price_per_hour' => null,
                'capacity_limit' => 1,
                'concurrent_capacity' => 1, // Only one booking at a time
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $beachActivitiesCategory->id,
                'name' => 'Futsal Court',
                'service_type' => 'Beach Sports',
                'description' => 'Beach futsal court with goals and ball provided',
                'booking_type' => 'fixed_slot',
                'slot_duration_minutes' => 90, // 1.5-hour slots
                'slot_price' => 200.00, // MVR 200 per 1.5 hours
                'price_per_hour' => null,
                'capacity_limit' => 1,
                'concurrent_capacity' => 1,
                'opening_time' => '07:00:00',
                'closing_time' => '19:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Water Sports (Flexible Duration)
        $waterSports = [
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Jet Ski',
                'service_type' => 'Water Sports',
                'description' => 'High-speed jet ski rental with safety equipment and brief training',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 350.00, // MVR 350 per hour
                'capacity_limit' => 5,
                'concurrent_capacity' => 5, // 5 jet skis available
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Catamaran Sailing',
                'service_type' => 'Water Sports',
                'description' => 'Guided catamaran sailing experience around the island',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 450.00, // MVR 450 per hour
                'capacity_limit' => 2,
                'concurrent_capacity' => 2, // 2 catamarans available
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Kayaking',
                'service_type' => 'Water Sports',
                'description' => 'Single or double kayak rental with paddles and life jackets',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 120.00, // MVR 120 per hour
                'capacity_limit' => 10,
                'concurrent_capacity' => 10, // 10 kayaks available
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Stand-Up Paddleboarding (SUP)',
                'service_type' => 'Water Sports',
                'description' => 'Paddleboard rental with safety equipment and basic instruction',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 100.00, // MVR 100 per hour
                'capacity_limit' => 8,
                'concurrent_capacity' => 8, // 8 paddleboards available
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Banana Boat Ride',
                'service_type' => 'Water Sports',
                'description' => 'Thrilling banana boat ride for groups, towed by speedboat',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 400.00, // MVR 400 per hour
                'capacity_limit' => 2,
                'concurrent_capacity' => 2, // 2 banana boats available
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Snorkeling',
                'service_type' => 'Water Sports',
                'description' => 'Snorkeling equipment rental with mask, snorkel, and fins',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 80.00, // MVR 80 per hour
                'capacity_limit' => 15,
                'concurrent_capacity' => 15, // 15 sets available
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Windsurfing',
                'service_type' => 'Water Sports',
                'description' => 'Windsurfing board rental with sail and safety equipment',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 250.00, // MVR 250 per hour
                'capacity_limit' => 4,
                'concurrent_capacity' => 4, // 4 boards available
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert all services
        DB::table('beach_services')->insert(array_merge($beachActivities, $waterSports));

        $this->command->info('Beach services seeded successfully!');
        $this->command->info('- 2 Beach Activities (Volleyball & Futsal)');
        $this->command->info('- 7 Water Sports (Jet Ski, Catamaran, Kayaking, SUP, Banana Boat, Snorkeling, Windsurfing)');
    }
}
