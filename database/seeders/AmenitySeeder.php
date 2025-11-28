<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing amenities and categories
        Amenity::query()->delete();
        AmenityCategory::query()->delete();

        // Get all hotels
        $hotels = Hotel::all();

        if ($hotels->isEmpty()) {
            $this->command->info('No hotels found. Please create hotels first.');
            return;
        }

        // Define amenity categories with their items
        $amenityData = [
            'Bathroom' => [
                'description' => 'Bathroom facilities and fixtures',
                'items' => [
                    ['name' => 'Bathtub', 'description' => 'Full-size bathtub for relaxing baths'],
                    ['name' => 'Walk-in Shower', 'description' => 'Spacious walk-in shower with glass doors'],
                    ['name' => 'Private Bathroom', 'description' => 'En-suite private bathroom'],
                    ['name' => 'Bidet', 'description' => 'Bidet for personal hygiene'],
                    ['name' => 'Heated Floors', 'description' => 'Underfloor heating in bathroom'],
                    ['name' => 'Rain Shower', 'description' => 'Overhead rain shower fixture'],
                    ['name' => 'Jacuzzi', 'description' => 'Whirlpool jacuzzi bathtub'],
                ],
            ],
            'Toiletries' => [
                'description' => 'Personal care and hygiene products',
                'items' => [
                    ['name' => 'Shampoo', 'description' => 'Premium quality shampoo'],
                    ['name' => 'Conditioner', 'description' => 'Hair conditioner'],
                    ['name' => 'Body Wash', 'description' => 'Luxury body wash'],
                    ['name' => 'Bar Soap', 'description' => 'Premium bar soap'],
                    ['name' => 'Toothbrush & Toothpaste', 'description' => 'Complimentary dental care kit'],
                    ['name' => 'Lotion', 'description' => 'Body lotion and moisturizer'],
                    ['name' => 'Shower Cap', 'description' => 'Disposable shower cap'],
                    ['name' => 'Shaving Kit', 'description' => 'Razor and shaving cream'],
                ],
            ],
            'Linens & Towels' => [
                'description' => 'Bed linens and bath towels',
                'items' => [
                    ['name' => 'Premium Bedding', 'description' => 'High-thread-count bed sheets'],
                    ['name' => 'Extra Pillows', 'description' => 'Additional pillows available'],
                    ['name' => 'Bath Towels', 'description' => 'Large bath towels'],
                    ['name' => 'Hand Towels', 'description' => 'Hand towels for bathroom'],
                    ['name' => 'Beach Towels', 'description' => 'Towels for beach use'],
                    ['name' => 'Bathrobes', 'description' => 'Plush bathrobes'],
                    ['name' => 'Slippers', 'description' => 'Complimentary room slippers'],
                ],
            ],
            'Electronics' => [
                'description' => 'Electronic devices and entertainment',
                'items' => [
                    ['name' => 'Flat-screen TV', 'description' => 'HD flat-screen television'],
                    ['name' => 'Cable/Satellite Channels', 'description' => 'Premium TV channels'],
                    ['name' => 'Free WiFi', 'description' => 'High-speed wireless internet'],
                    ['name' => 'Telephone', 'description' => 'In-room telephone'],
                    ['name' => 'Hair Dryer', 'description' => 'Professional hair dryer'],
                    ['name' => 'Iron & Ironing Board', 'description' => 'Iron with ironing board'],
                    ['name' => 'Safe', 'description' => 'In-room electronic safe'],
                    ['name' => 'Bluetooth Speaker', 'description' => 'Wireless Bluetooth speaker'],
                ],
            ],
            'Climate Control' => [
                'description' => 'Temperature and air quality control',
                'items' => [
                    ['name' => 'Air Conditioning', 'description' => 'Individual climate control'],
                    ['name' => 'Heating', 'description' => 'Room heating system'],
                    ['name' => 'Ceiling Fan', 'description' => 'Overhead ceiling fan'],
                    ['name' => 'Air Purifier', 'description' => 'HEPA air purification system'],
                ],
            ],
            'Furniture & Comfort' => [
                'description' => 'Room furniture and comfort items',
                'items' => [
                    ['name' => 'Work Desk', 'description' => 'Desk with ergonomic chair'],
                    ['name' => 'Seating Area', 'description' => 'Comfortable seating with sofa'],
                    ['name' => 'Wardrobe/Closet', 'description' => 'Spacious wardrobe with hangers'],
                    ['name' => 'Full-length Mirror', 'description' => 'Floor-standing mirror'],
                    ['name' => 'Blackout Curtains', 'description' => 'Light-blocking curtains'],
                    ['name' => 'Balcony/Terrace', 'description' => 'Private outdoor space'],
                ],
            ],
            'Minibar & Refreshments' => [
                'description' => 'In-room dining and beverages',
                'items' => [
                    ['name' => 'Minibar', 'description' => 'Stocked minibar refrigerator'],
                    ['name' => 'Coffee Maker', 'description' => 'In-room coffee machine'],
                    ['name' => 'Electric Kettle', 'description' => 'Electric water kettle'],
                    ['name' => 'Complimentary Water', 'description' => 'Daily bottled water'],
                    ['name' => 'Tea & Coffee Supplies', 'description' => 'Selection of teas and coffee'],
                    ['name' => 'Mini Fridge', 'description' => 'Small refrigerator for personal use'],
                ],
            ],
            'Modern Features' => [
                'description' => 'Smart and modern amenities',
                'items' => [
                    ['name' => 'USB Charging Ports', 'description' => 'Built-in USB outlets'],
                    ['name' => 'Smart Lighting', 'description' => 'Adjustable LED lighting'],
                    ['name' => 'Smart TV', 'description' => 'Smart TV with streaming apps'],
                    ['name' => 'Wireless Charging Pad', 'description' => 'Qi wireless charger'],
                    ['name' => 'Voice Assistant', 'description' => 'Smart home voice control'],
                ],
            ],
            'Accessibility' => [
                'description' => 'Accessibility and safety features',
                'items' => [
                    ['name' => 'Wheelchair Accessible', 'description' => 'Room designed for wheelchair access'],
                    ['name' => 'Grab Rails', 'description' => 'Safety rails in bathroom'],
                    ['name' => 'Emergency Call Button', 'description' => 'Emergency assistance button'],
                    ['name' => 'Lowered Fixtures', 'description' => 'Accessible height fixtures'],
                ],
            ],
            'Additional Services' => [
                'description' => 'Extra services and conveniences',
                'items' => [
                    ['name' => 'Daily Housekeeping', 'description' => 'Daily room cleaning service'],
                    ['name' => 'Turndown Service', 'description' => 'Evening turndown service'],
                    ['name' => 'Wake-up Service', 'description' => 'Wake-up call service'],
                    ['name' => 'Room Service Available', 'description' => '24-hour room service'],
                    ['name' => 'Laundry Service', 'description' => 'Laundry and dry cleaning'],
                ],
            ],
        ];

        // Create categories and items for each hotel
        foreach ($hotels as $hotel) {
            $this->command->info("Creating amenities for hotel: {$hotel->name}");

            $sortOrder = 0;
            foreach ($amenityData as $categoryName => $categoryInfo) {
                // Create category
                $category = AmenityCategory::create([
                    'hotel_id' => $hotel->id,
                    'name' => $categoryName,
                    'description' => $categoryInfo['description'],
                    'sort_order' => $sortOrder++,
                    'is_active' => true,
                ]);

                // Create items for this category
                $itemSortOrder = 0;
                foreach ($categoryInfo['items'] as $itemData) {
                    Amenity::create([
                        'hotel_id' => $hotel->id,
                        'category_id' => $category->id,
                        'name' => $itemData['name'],
                        'description' => $itemData['description'],
                        'sort_order' => $itemSortOrder++,
                        'is_active' => true,
                    ]);
                }

                $this->command->info("  Created category '{$categoryName}' with " . count($categoryInfo['items']) . " items");
            }
        }

        $this->command->info('Amenity seeding completed successfully!');
    }
}
