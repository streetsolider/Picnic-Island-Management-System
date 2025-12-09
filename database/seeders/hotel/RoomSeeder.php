<?php

namespace Database\Seeders\Hotel;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomTypePricing;
use App\Models\ViewPricing;
use App\Models\SeasonalPricing;
use App\Models\DayTypePricing;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all hotels
        $hotels = Hotel::orderBy('star_rating', 'desc')->get();

        if ($hotels->isEmpty()) {
            $this->command->error('No hotels found! Please run HotelSeeder first.');
            return;
        }

        foreach ($hotels as $hotel) {
            $this->command->info("Processing {$hotel->name} ({$hotel->star_rating} stars)...");

            // Clear existing rooms and pricing for this hotel
            $this->command->info("  Clearing existing data...");
            $hotel->rooms()->delete();
            RoomTypePricing::where('hotel_id', $hotel->id)->delete();
            ViewPricing::where('hotel_id', $hotel->id)->delete();
            SeasonalPricing::where('hotel_id', $hotel->id)->delete();
            DayTypePricing::where('hotel_id', $hotel->id)->delete();

            // Create pricing structures
            $this->command->info("  Creating pricing structures...");
            $this->createRoomTypePricing($hotel);
            $this->createViewPricing($hotel->id);
            $this->createSeasonalPricing($hotel->id);
            $this->createDayTypePricing($hotel->id);

            // Create rooms
            $this->command->info("  Creating rooms...");
            $this->createRooms($hotel);

            $this->command->info("  ✓ {$hotel->name} complete!");
        }

        $this->command->info("\n✓ All hotels seeded successfully with rooms and pricing!");
    }

    /**
     * Create room type base pricing based on hotel star rating
     */
    private function createRoomTypePricing(Hotel $hotel): void
    {
        // Tiered pricing based on star rating
        $pricingTiers = [
            5 => [ // 5-star
                'standard' => 1000,
                'superior' => 1500,
                'deluxe' => 2500,
                'suite' => 4000,
                'family' => 2000,
            ],
            4 => [ // 4-star
                'standard' => 800,
                'superior' => 1200,
                'deluxe' => 2000,
                'suite' => 3500,
                'family' => 1500,
            ],
            3 => [ // 3-star
                'standard' => 600,
                'superior' => 900,
                'deluxe' => 1500,
                'family' => 1200,
            ],
            2 => [ // 2-star
                'standard' => 400,
                'superior' => 600,
                'family' => 800,
            ],
        ];

        $pricing = $pricingTiers[$hotel->star_rating] ?? $pricingTiers[3];

        foreach ($pricing as $roomType => $basePrice) {
            RoomTypePricing::create([
                'hotel_id' => $hotel->id,
                'room_type' => $roomType,
                'base_price' => $basePrice,
                'currency' => 'MVR',
                'is_active' => true,
            ]);
        }
    }

    /**
     * Create view pricing modifiers (same for all hotels)
     */
    private function createViewPricing(int $hotelId): void
    {
        $views = [
            ['view' => 'garden', 'modifier_type' => 'fixed', 'modifier_value' => 0],
            ['view' => 'beach', 'modifier_type' => 'percentage', 'modifier_value' => 10], // +10%
        ];

        foreach ($views as $viewData) {
            ViewPricing::create([
                'hotel_id' => $hotelId,
                'view' => $viewData['view'],
                'modifier_type' => $viewData['modifier_type'],
                'modifier_value' => $viewData['modifier_value'],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Create seasonal pricing (same % for all hotels)
     */
    private function createSeasonalPricing(int $hotelId): void
    {
        // Peak Season (December - March)
        SeasonalPricing::create([
            'hotel_id' => $hotelId,
            'season_name' => 'Peak Season',
            'start_date' => Carbon::create(2025, 12, 1),
            'end_date' => Carbon::create(2026, 3, 31),
            'modifier_type' => 'percentage',
            'modifier_value' => 12, // +12% (was 30%)
            'is_active' => true,
            'priority' => 10,
        ]);
    }

    /**
     * Create day type pricing (same % for all hotels)
     */
    private function createDayTypePricing(int $hotelId): void
    {
        // Weekend pricing (Friday-Saturday)
        DayTypePricing::create([
            'hotel_id' => $hotelId,
            'day_type_name' => 'Weekend',
            'applicable_days' => [5, 6], // Friday, Saturday
            'modifier_type' => 'percentage',
            'modifier_value' => 5, // +5% (was 15%)
            'is_active' => true,
        ]);
    }

    /**
     * Create rooms based on hotel tier
     */
    private function createRooms(Hotel $hotel): void
    {
        $roomConfigurations = $this->getRoomConfigurationsByTier($hotel);

        $roomNumber = 101;
        foreach ($roomConfigurations as $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                Room::create([
                    'hotel_id' => $hotel->id,
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

    /**
     * Get room configurations based on hotel star rating
     */
    private function getRoomConfigurationsByTier(Hotel $hotel): array
    {
        switch ($hotel->star_rating) {
            case 5: // Paradise Bay Hotel - 50 rooms
                return [
                    // STANDARD (10 rooms) - Garden & Beach
                    ['count' => 3, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 1],
                    ['count' => 3, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // SUPERIOR (12 rooms) - Garden & Beach
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // DELUXE (12 rooms) - More Beach View
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 4, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 4, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // SUITE (10 rooms) - Premium Beach View
                    ['count' => 2, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 8, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],

                    // FAMILY (6 rooms)
                    ['count' => 4, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'triple', 'view' => 'garden', 'occupancy' => 6],
                    ['count' => 2, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'quad', 'view' => 'beach', 'occupancy' => 8],
                ];

            case 4: // Island View Hotel - 40 rooms
                return [
                    // STANDARD (12 rooms)
                    ['count' => 4, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 4, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // SUPERIOR (10 rooms)
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // DELUXE (8 rooms)
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // SUITE (4 rooms)
                    ['count' => 2, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'suite', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],

                    // FAMILY (6 rooms)
                    ['count' => 4, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'triple', 'view' => 'garden', 'occupancy' => 6],
                    ['count' => 2, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'quad', 'view' => 'beach', 'occupancy' => 8],
                ];

            case 3: // Sunset Inn - 30 rooms
                return [
                    // STANDARD (12 rooms) - Mostly Garden
                    ['count' => 4, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 4, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // SUPERIOR (8 rooms)
                    ['count' => 3, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],
                    ['count' => 1, 'type' => 'superior', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'beach', 'occupancy' => 4],

                    // DELUXE (4 rooms) - Limited
                    ['count' => 2, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 1, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],
                    ['count' => 1, 'type' => 'deluxe', 'bed_size' => 'king', 'bed_count' => 'single', 'view' => 'beach', 'occupancy' => 2],

                    // FAMILY (6 rooms)
                    ['count' => 4, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'triple', 'view' => 'garden', 'occupancy' => 6],
                    ['count' => 2, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'quad', 'view' => 'beach', 'occupancy' => 8],
                ];

            case 2: // Coastal Retreat - 20 rooms (Garden View Only, No Deluxe/Suite)
                return [
                    // STANDARD (10 rooms) - All Garden View
                    ['count' => 4, 'type' => 'standard', 'bed_size' => 'twin', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 3, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 3, 'type' => 'standard', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],

                    // SUPERIOR (4 rooms) - All Garden View
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'single', 'view' => 'garden', 'occupancy' => 2],
                    ['count' => 2, 'type' => 'superior', 'bed_size' => 'queen', 'bed_count' => 'double', 'view' => 'garden', 'occupancy' => 4],

                    // FAMILY (6 rooms) - All Garden View
                    ['count' => 4, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'triple', 'view' => 'garden', 'occupancy' => 6],
                    ['count' => 2, 'type' => 'family', 'bed_size' => 'twin', 'bed_count' => 'quad', 'view' => 'garden', 'occupancy' => 8],
                ];

            default:
                return [];
        }
    }
}
