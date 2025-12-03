<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomTypePricing;
use App\Models\ViewPricing;
use App\Models\SeasonalPricing;
use App\Models\DayTypePricing;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ParadiseBayRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Paradise Bay Hotel
        $hotel = Hotel::where('name', 'Paradise Bay Hotel')->first();

        if (!$hotel) {
            $this->command->error('Paradise Bay Hotel not found! Please run HotelSeeder first.');
            return;
        }

        $this->command->info("Clearing existing rooms for {$hotel->name}...");

        // Clear existing rooms and pricing for this hotel
        $hotel->rooms()->delete();

        // Use eloquent to delete related pricing
        RoomTypePricing::where('hotel_id', $hotel->id)->delete();
        ViewPricing::where('hotel_id', $hotel->id)->delete();
        SeasonalPricing::where('hotel_id', $hotel->id)->delete();
        DayTypePricing::where('hotel_id', $hotel->id)->delete();

        $this->command->info('Creating room type base pricing...');
        $this->createRoomTypePricing($hotel->id);

        $this->command->info('Creating view pricing modifiers...');
        $this->createViewPricing($hotel->id);

        $this->command->info('Creating seasonal pricing...');
        $this->createSeasonalPricing($hotel->id);

        $this->command->info('Creating weekend pricing...');
        $this->createDayTypePricing($hotel->id);

        $this->command->info('Creating rooms...');
        $this->createRooms($hotel->id);

        $this->command->info("✓ Successfully seeded {$hotel->name} with rooms and pricing strategies!");
    }

    /**
     * Create room type base pricing
     */
    private function createRoomTypePricing(int $hotelId): void
    {
        $pricing = [
            ['room_type' => 'standard', 'base_price' => 800],   // Starting price
            ['room_type' => 'superior', 'base_price' => 1200],
            ['room_type' => 'deluxe', 'base_price' => 2000],
            ['room_type' => 'suite', 'base_price' => 3500],
            ['room_type' => 'family', 'base_price' => 1500],    // Family rooms vary by size
        ];

        foreach ($pricing as $price) {
            RoomTypePricing::updateOrCreate(
                [
                    'hotel_id' => $hotelId,
                    'room_type' => $price['room_type'],
                ],
                [
                    'base_price' => $price['base_price'],
                    'currency' => 'MVR',
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Create view pricing modifiers
     */
    private function createViewPricing(int $hotelId): void
    {
        $views = [
            ['view' => 'garden', 'modifier_type' => 'fixed', 'modifier_value' => 0],
            ['view' => 'beach', 'modifier_type' => 'percentage', 'modifier_value' => 25],
        ];

        foreach ($views as $viewData) {
            try {
                ViewPricing::updateOrCreate(
                    ['hotel_id' => $hotelId, 'view' => $viewData['view']],
                    [
                        'modifier_type' => $viewData['modifier_type'],
                        'modifier_value' => $viewData['modifier_value'],
                        'is_active' => true,
                    ]
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // If duplicate entry, just update it
                ViewPricing::where('hotel_id', $hotelId)
                    ->where('view', $viewData['view'])
                    ->update([
                        'modifier_type' => $viewData['modifier_type'],
                        'modifier_value' => $viewData['modifier_value'],
                        'is_active' => true,
                    ]);
            }
        }
    }

    /**
     * Create seasonal pricing
     */
    private function createSeasonalPricing(int $hotelId): void
    {
        // Peak Season (December - March) - High tourist season
        SeasonalPricing::create([
            'hotel_id' => $hotelId,
            'season_name' => 'Peak Season',
            'start_date' => Carbon::create(2025, 12, 1),
            'end_date' => Carbon::create(2026, 3, 31),
            'modifier_type' => 'percentage',
            'modifier_value' => 30,  // 30% increase
            'is_active' => true,
            'priority' => 10,
        ]);

        // Off Season (April - November) - Standard rates (no modifier needed)
        // Base prices apply during off-season
    }

    /**
     * Create day type pricing (Weekend rates)
     */
    private function createDayTypePricing(int $hotelId): void
    {
        // Weekend pricing (Friday-Saturday)
        // 0 = Sunday, 5 = Friday, 6 = Saturday
        DayTypePricing::create([
            'hotel_id' => $hotelId,
            'day_type_name' => 'Weekend',
            'applicable_days' => [5, 6], // Friday, Saturday
            'modifier_type' => 'percentage',
            'modifier_value' => 15,  // 15% weekend surcharge
            'is_active' => true,
        ]);
    }

    /**
     * Create logical room configurations
     */
    private function createRooms(int $hotelId): void
    {
        $roomConfigurations = [
            // STANDARD ROOMS (MVR 800 base) - Garden View
            ['count' => 8, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 1],
            ['count' => 6, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 2],
            ['count' => 8, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
            // STANDARD ROOMS - Beach View
            ['count' => 6, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

            // SUPERIOR ROOMS (MVR 1200 base) - Garden View
            ['count' => 5, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
            ['count' => 5, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
            // SUPERIOR ROOMS - Beach View
            ['count' => 8, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],

            // DELUXE ROOMS (MVR 2000 base) - Garden View
            ['count' => 4, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
            // DELUXE ROOMS - Beach View
            ['count' => 6, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
            ['count' => 5, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

            // SUITE ROOMS (MVR 3500 base) - Garden View
            ['count' => 3, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
            // SUITE ROOMS - Beach View
            ['count' => 5, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],

            // FAMILY ROOMS (MVR 1500 base) - Garden View
            ['count' => 6, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'triple', 'view' => 'garden', 'occupancy' => 6],
            // FAMILY ROOMS - Beach View
            ['count' => 4, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'quad', 'view' => 'beach', 'occupancy' => 8],
        ];

        $roomNumber = 101;
        foreach ($roomConfigurations as $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                Room::create([
                    'hotel_id' => $hotelId,
                    'room_number' => (string) $roomNumber,
                    'room_type' => $config['type'],
                    'bed_size' => $config['bed_size'],
                    'bed_count' => $config['bed_count'],
                    'view' => $config['view'],
                    'max_occupancy' => $config['occupancy'],
                    'is_available' => true,
                    'is_active' => true,
                ]);

                $roomNumber++;
            }
        }
    }
}
