<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\PromotionalDiscount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionalDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing promotional discounts
        PromotionalDiscount::truncate();

        // Get all hotels
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // 1. Family Fun Package - Multi-room discount
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Family Fun Package',
                'promotion_description' => 'Book 2 or more rooms and save 15% on your entire stay!',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'start_date' => null, // Always active
                'end_date' => null,
                'minimum_rooms' => 2,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => null, // All room types
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 10,
            ]);

            // 2. Group Booking Special - 3+ rooms
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Group Booking Special',
                'promotion_description' => 'Traveling with a group? Book 3+ rooms and get MVR 1,000 off!',
                'discount_type' => 'fixed',
                'discount_value' => 1000.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => 3,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => null,
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 15, // Higher priority than Family Fun Package
            ]);

            // 3. Local Resident Discount - Promo code required
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Local Resident Special',
                'promotion_description' => 'Maldivian residents enjoy 20% off with promo code!',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => null,
                'promo_code' => 'LOCAL2025-H' . $hotel->id,
                'is_active' => true,
                'priority' => 20, // High priority
            ]);

            // 4. Early Bird Booking - 30 days advance
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Early Bird Special',
                'promotion_description' => 'Book 30 days in advance and save 10%!',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => 30,
                'applicable_room_types' => null,
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 5,
            ]);

            // 5. Weekend Getaway - 2-3 nights only
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Weekend Getaway',
                'promotion_description' => 'Perfect for a quick escape! 2-3 night stays get MVR 500 off.',
                'discount_type' => 'fixed',
                'discount_value' => 500.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => 2,
                'maximum_nights' => 3,
                'booking_advance_days' => null,
                'applicable_room_types' => null,
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 8,
            ]);

            // 6. Deluxe & Suite Promotion - Room type specific
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Luxury Experience',
                'promotion_description' => 'Upgrade to Deluxe or Suite rooms and save 12%!',
                'discount_type' => 'percentage',
                'discount_value' => 12.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => ['Deluxe', 'Suite'],
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 7,
            ]);

            // 7. Seasonal Promotion - Summer Special (May to September)
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Summer Island Escape',
                'promotion_description' => 'Summer special: 15% off all bookings from May to September!',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'start_date' => now()->year . '-05-01',
                'end_date' => now()->year . '-09-30',
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => null,
                'promo_code' => null, // Auto-apply
                'is_active' => true,
                'priority' => 6,
            ]);

            // 8. Last Minute Deal - Promo code
            PromotionalDiscount::create([
                'hotel_id' => $hotel->id,
                'promotion_name' => 'Last Minute Deal',
                'promotion_description' => 'Spontaneous traveler? Use code for last-minute savings!',
                'discount_type' => 'fixed',
                'discount_value' => 750.00,
                'start_date' => null,
                'end_date' => null,
                'minimum_rooms' => null,
                'maximum_rooms' => null,
                'minimum_nights' => null,
                'maximum_nights' => null,
                'booking_advance_days' => null,
                'applicable_room_types' => null,
                'promo_code' => 'LASTMINUTE-H' . $hotel->id,
                'is_active' => true,
                'priority' => 12,
            ]);
        }
    }
}
