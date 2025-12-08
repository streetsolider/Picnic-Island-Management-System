<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeachActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Water Sports',
                'description' => 'Exciting water-based activities including jet skiing, catamarans, and more',
                'icon' => '🏄',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beach Sports',
                'description' => 'Beach sports and recreational activities including volleyball, basketball, and football',
                'icon' => '🏐',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beach Huts',
                'description' => 'Private beach huts for relaxation, cooking, and BBQ activities',
                'icon' => '🏖️',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('beach_activity_categories')->insert($categories);

        $this->command->info('Beach activity categories seeded successfully!');

        // Get category IDs
        $waterSportsCategory = DB::table('beach_activity_categories')
            ->where('name', 'Water Sports')
            ->first();

        $beachSportsCategory = DB::table('beach_activity_categories')
            ->where('name', 'Beach Sports')
            ->first();

        $beachHutsCategory = DB::table('beach_activity_categories')
            ->where('name', 'Beach Huts')
            ->first();

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
                'price_per_hour' => 350.00,
                'capacity_limit' => 5,
                'concurrent_capacity' => 5,
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Catamaran Sailing',
                'service_type' => 'Water Sports',
                'description' => 'Guided catamaran sailing experience around the island',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 450.00,
                'capacity_limit' => 2,
                'concurrent_capacity' => 2,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Kayaking',
                'service_type' => 'Water Sports',
                'description' => 'Single or double kayak rental with paddles and life jackets',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 120.00,
                'capacity_limit' => 10,
                'concurrent_capacity' => 10,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Stand-Up Paddleboarding (SUP)',
                'service_type' => 'Water Sports',
                'description' => 'Paddleboard rental with safety equipment and basic instruction',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 100.00,
                'capacity_limit' => 8,
                'concurrent_capacity' => 8,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Banana Boat Ride',
                'service_type' => 'Water Sports',
                'description' => 'Thrilling banana boat ride for groups, towed by speedboat',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 400.00,
                'capacity_limit' => 2,
                'concurrent_capacity' => 2,
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Snorkeling',
                'service_type' => 'Water Sports',
                'description' => 'Snorkeling equipment rental with mask, snorkel, and fins',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 80.00,
                'capacity_limit' => 15,
                'concurrent_capacity' => 15,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $waterSportsCategory->id,
                'name' => 'Windsurfing',
                'service_type' => 'Water Sports',
                'description' => 'Windsurfing board rental with sail and safety equipment',
                'booking_type' => 'flexible_duration',
                'slot_duration_minutes' => null,
                'slot_price' => null,
                'price_per_hour' => 250.00,
                'capacity_limit' => 4,
                'concurrent_capacity' => 4,
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Beach Sports (Fixed Slots)
        $beachSports = [
            [
                'beach_activity_category_id' => $beachSportsCategory->id,
                'name' => 'Beach Volleyball Court',
                'service_type' => 'Beach Sports',
                'description' => 'Professional beach volleyball court with net and equipment provided',
                'booking_type' => 'fixed_slot',
                'slot_duration_minutes' => 60,
                'slot_price' => 150.00,
                'price_per_hour' => null,
                'capacity_limit' => 1,
                'concurrent_capacity' => 1,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'beach_activity_category_id' => $beachSportsCategory->id,
                'name' => 'Futsal Court',
                'service_type' => 'Beach Sports',
                'description' => 'Beach futsal court with goals and ball provided',
                'booking_type' => 'fixed_slot',
                'slot_duration_minutes' => 90,
                'slot_price' => 200.00,
                'price_per_hour' => null,
                'capacity_limit' => 1,
                'concurrent_capacity' => 1,
                'opening_time' => '07:00:00',
                'closing_time' => '19:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Beach Huts (Fixed Slots)
        $beachHuts = [];
        for ($i = 1; $i <= 8; $i++) {
            $beachHuts[] = [
                'beach_activity_category_id' => $beachHutsCategory->id,
                'name' => "Beach Hut #{$i}",
                'service_type' => 'Beach Huts',
                'description' => 'Private beach hut with seating, shade, and ocean view. Perfect for relaxation.',
                'booking_type' => 'fixed_slot',
                'slot_duration_minutes' => 180,
                'slot_price' => 500.00,
                'price_per_hour' => null,
                'capacity_limit' => 1,
                'concurrent_capacity' => 1,
                'opening_time' => '08:00:00',
                'closing_time' => '20:00:00',
                'assigned_staff_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all beach services
        DB::table('beach_services')->insert(array_merge($waterSports, $beachSports, $beachHuts));

        $this->command->info('Beach services seeded successfully!');
        $this->command->info('- 7 Water Sports');
        $this->command->info('- 2 Beach Sports');
        $this->command->info('- 8 Beach Huts');
    }
}
