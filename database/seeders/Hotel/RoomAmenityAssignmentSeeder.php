<?php

namespace Database\Seeders\Hotel;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomAmenityAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting intelligent room amenity assignment...');

        // Clear existing room-amenity assignments
        DB::table('room_amenity')->truncate();
        $this->command->info('Cleared existing room-amenity assignments');

        // Get all hotels with their rooms and amenities
        $hotels = Hotel::with(['rooms', 'amenities.category'])->get();

        if ($hotels->isEmpty()) {
            $this->command->error('No hotels found! Please run HotelSeeder and AmenitySeeder first.');
            return;
        }

        foreach ($hotels as $hotel) {
            $this->command->info("\nProcessing {$hotel->name} ({$hotel->star_rating} stars)...");
            $this->assignAmenitiesToHotelRooms($hotel);
        }

        $this->command->info("\n✓ Room amenity assignment completed successfully!");
    }

    /**
     * Assign amenities to all rooms in a hotel
     */
    private function assignAmenitiesToHotelRooms(Hotel $hotel): void
    {
        $rooms = $hotel->rooms;

        if ($rooms->isEmpty()) {
            $this->command->warn("  No rooms found for {$hotel->name}");
            return;
        }

        // Get all amenities for this hotel, grouped by name for easy lookup
        $amenitiesByName = $hotel->amenities->keyBy('name');

        $totalAssignments = 0;

        foreach ($rooms as $room) {
            $amenityNames = $this->getAmenitiesForRoom($room, $hotel->star_rating);
            $amenityIds = [];

            foreach ($amenityNames as $amenityName) {
                if (isset($amenitiesByName[$amenityName])) {
                    $amenityIds[] = $amenitiesByName[$amenityName]->id;
                }
            }

            // Attach amenities to room
            $room->amenities()->attach($amenityIds);
            $totalAssignments += count($amenityIds);

            $this->command->info("  Room {$room->room_number} ({$room->room_type}, {$room->view} view): " . count($amenityIds) . " amenities");
        }

        $this->command->info("  Total: {$totalAssignments} amenity assignments for {$hotel->name}");
    }

    /**
     * Get amenities for a specific room based on hotel rating, room type, and view
     */
    private function getAmenitiesForRoom(Room $room, int $starRating): array
    {
        // Base amenities - ALL rooms get these
        $amenities = [
            'Private Bathroom',
            'Air Conditioning',
            'Free WiFi',
            'Daily Housekeeping',
            'Bath Towels',
            'Hand Towels',
            'Premium Bedding',
        ];

        // Add amenities based on star rating tier
        $amenities = array_merge($amenities, $this->getStarRatingAmenities($starRating, $room->room_type));

        // Add view-specific amenities
        if ($room->view === 'beach') {
            $amenities[] = 'Beach Towels';
        }

        // Add family room specific amenities
        if ($room->room_type === 'family') {
            $amenities[] = 'Extra Pillows';
            $amenities[] = 'Seating Area';
        }

        // Remove duplicates
        return array_unique($amenities);
    }

    /**
     * Get amenities based on star rating and room type
     */
    private function getStarRatingAmenities(int $starRating, string $roomType): array
    {
        $amenities = [];

        // 2-STAR AMENITIES
        if ($starRating >= 2) {
            // All 2-star rooms
            $amenities = array_merge($amenities, [
                'Flat-screen TV',
                'Coffee Maker',
                'Wardrobe/Closet',
                'Ceiling Fan',
            ]);

            // 2-star Superior and above
            if (in_array($roomType, ['superior', 'deluxe', 'suite', 'family'])) {
                $amenities = array_merge($amenities, [
                    'Hair Dryer',
                    'Mini Fridge',
                    'Electric Kettle',
                ]);
            }
        }

        // 3-STAR AMENITIES (builds on 2-star)
        if ($starRating >= 3) {
            // All 3-star rooms
            $amenities = array_merge($amenities, [
                'Blackout Curtains',
                'Iron & Ironing Board',
                'Telephone',
            ]);

            // 3-star Superior and above
            if (in_array($roomType, ['superior', 'deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Cable/Satellite Channels',
                    'Complimentary Water',
                    'Bar Soap',
                    'Shower Cap',
                ]);
            }

            // 3-star Deluxe and above
            if (in_array($roomType, ['deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Safe',
                    'Work Desk',
                    'Full-length Mirror',
                    'Shampoo',
                    'Conditioner',
                ]);
            }
        }

        // 4-STAR AMENITIES (builds on 3-star)
        if ($starRating >= 4) {
            // All 4-star rooms
            $amenities = array_merge($amenities, [
                'Rain Shower',
                'Slippers',
                'Bathrobes',
                'Minibar',
                'Tea & Coffee Supplies',
                'Body Wash',
            ]);

            // 4-star Superior and above
            if (in_array($roomType, ['superior', 'deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Smart TV',
                    'USB Charging Ports',
                    'Lotion',
                    'Toothbrush & Toothpaste',
                ]);
            }

            // 4-star Deluxe and above
            if (in_array($roomType, ['deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Walk-in Shower',
                    'Balcony/Terrace',
                    'Shaving Kit',
                ]);
            }

            // 4-star Suite only
            if ($roomType === 'suite') {
                $amenities = array_merge($amenities, [
                    'Jacuzzi',
                    'Bluetooth Speaker',
                    'Smart Lighting',
                    'Turndown Service',
                ]);
            }
        }

        // 5-STAR AMENITIES (builds on 4-star)
        if ($starRating >= 5) {
            // All 5-star rooms
            $amenities = array_merge($amenities, [
                'Heated Floors',
                'Air Purifier',
                'Wireless Charging Pad',
                'Heating',
            ]);

            // 5-star Superior and above
            if (in_array($roomType, ['superior', 'deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Voice Assistant',
                    'Room Service Available',
                ]);
            }

            // 5-star Deluxe and above
            if (in_array($roomType, ['deluxe', 'suite'])) {
                $amenities = array_merge($amenities, [
                    'Laundry Service',
                    'Wake-up Service',
                ]);
            }

            // 5-star Suite only
            if ($roomType === 'suite') {
                $amenities = array_merge($amenities, [
                    'Bidet',
                    'Bathtub',
                    'Grab Rails',
                    'Emergency Call Button',
                ]);
            }
        }

        return $amenities;
    }
}
