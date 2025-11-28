<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Paradise Bay Resort Hotel
        $hotel = Hotel::where('name', 'Paradise Bay Resort')->first();

        if (!$hotel) {
            $this->command->error('Paradise Bay hotel not found!');
            return;
        }

        $this->command->info('Seeding policies for Paradise Bay Hotel...');

        // Delete existing policies to avoid duplicates
        HotelPolicy::where('hotel_id', $hotel->id)->delete();

        // Cancellation Policy
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'cancellation',
            'title' => 'Flexible Cancellation Policy',
            'description' => "Free Cancellation: Cancel up to 48 hours before your check-in date for a full refund.

Late Cancellation: Cancellations made within 48 hours of check-in will incur a charge of one night's stay.

No-Show: Failure to arrive at the hotel without prior cancellation will result in a charge for the full reservation amount.

Group Bookings: For reservations of 5 or more rooms, special cancellation terms may apply. Please contact our reservations team for details.

Peak Season: During peak seasons (December 20 - January 10, July 1 - August 31), a 7-day advance cancellation notice is required for a full refund.

Refund Processing: All approved refunds will be processed within 7-10 business days to the original payment method.",
            'is_active' => true,
        ]);

        // Check-in/Check-out Policy
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'check_in_out',
            'title' => 'Check-in & Check-out Times',
            'description' => "Check-in Time: 10:00 AM (Early check-in subject to availability)

Check-out Time: 9:00 AM (Late check-out available upon request with additional charges)

Early Check-in: If you arrive before 10:00 AM, we'll do our best to accommodate you. Early check-in is subject to room availability and may incur an additional charge of MVR 500.

Late Check-out: Available until 12:00 PM for MVR 750, or until 3:00 PM for MVR 1,500. Late check-out is subject to availability and must be requested at least 24 hours in advance.

Express Check-in: Available for guests who complete online check-in prior to arrival.

Luggage Storage: Complimentary luggage storage is available both before check-in and after check-out at our front desk.",
            'is_active' => true,
        ]);

        // Payment Policy
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'payment',
            'title' => 'Payment Terms & Conditions',
            'description' => "Accepted Payment Methods:
- Credit Cards (Visa, Mastercard, American Express)
- Debit Cards
- Bank Transfer (MVR only)
- Cash (MVR or USD)

Deposit Requirement: A deposit of 30% of the total booking amount is required at the time of reservation to confirm your booking.

Balance Payment: The remaining balance must be paid upon check-in or at least 7 days before arrival for advance bookings.

Currency: All rates are quoted in Maldivian Rufiyaa (MVR). Payments in other currencies will be converted at the prevailing exchange rate on the date of payment.

Credit Card Authorization: A valid credit card is required at check-in for incidental charges, even if the room has been prepaid.

Security Deposit: A refundable security deposit of MVR 1,000 per room is required at check-in and will be refunded upon check-out, subject to room inspection.

Service Charge & Tax: All rates are subject to applicable government taxes and a 10% service charge, which will be added to your final bill.",
            'is_active' => true,
        ]);

        // House Rules
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'house_rules',
            'title' => 'Hotel House Rules',
            'description' => "Smoking Policy: Paradise Bay is a smoke-free property. Smoking is only permitted in designated outdoor areas. Violation will result in a cleaning fee of MVR 2,500.

Noise Policy: Quiet hours are from 10:00 PM to 7:00 AM. Please be considerate of other guests during these hours.

Pets Policy: We welcome small pets (under 10kg) with prior approval. A non-refundable pet fee of MVR 1,500 per stay applies. Pets must be kept on a leash in public areas.

Visitor Policy: Registered guests only are allowed in guest rooms. Day visitors may use public facilities until 8:00 PM with prior notice to the front desk.

Swimming Pool: Pool hours are 6:00 AM to 10:00 PM. Children under 12 must be supervised by an adult at all times.

Beach Access: Private beach access is available to all guests. Please use designated pathways and respect marked conservation areas.

Dress Code: Smart casual attire is required in our restaurants during dinner service. Beachwear is not permitted in indoor dining areas.

Lost and Found: Items left behind will be kept for 30 days. Please contact us to arrange return shipping at your expense.",
            'is_active' => true,
        ]);

        // Age Restriction (Soft policy)
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'age_restriction',
            'title' => 'Age Policy & Child Guidelines',
            'description' => "Minimum Check-in Age: The primary guest must be at least 18 years of age to check in.

Children Welcome: We warmly welcome families with children of all ages.

Infant Stay: Children under 2 years stay free of charge when using existing bedding.

Child Rates: Children aged 2-11 are charged at 50% of the adult rate when sharing a room with parents.

Extra Bed: Extra beds for children can be provided at MVR 500 per night.

Children's Activities: Supervised activities are available for children aged 4-12 at our Kids Club (complimentary for hotel guests).

Babysitting Services: Professional babysitting services can be arranged with 24 hours advance notice at MVR 300 per hour.

Safety: For safety reasons, children must be supervised by adults in the pool, beach, and gym areas.",
            'is_active' => true,
        ]);

        // Damage & Deposit Policy
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'damage_deposit',
            'title' => 'Security Deposit & Damage Policy',
            'description' => "Security Deposit: A refundable security deposit of MVR 1,000 is required upon check-in. This can be paid by credit card authorization or cash.

Deposit Refund: The security deposit will be refunded within 24 hours of check-out, pending a room inspection. Cash deposits will be returned immediately if no damages are found.

Room Inspection: All rooms are inspected before and after each stay to ensure no damage has occurred.

Damages: Guests are responsible for any damage to the room or hotel property during their stay. Charges for damages will be deducted from the security deposit or charged to the credit card on file.

Pricing for Common Items:
- Towel replacement: MVR 150 each
- Bedding replacement: MVR 500 per set
- Broken glassware: MVR 50-200 per item
- TV remote: MVR 300
- Room key card: MVR 100
- Excessive cleaning: MVR 1,000-5,000

Smoking Violation: A cleaning fee of MVR 2,500 will be charged if smoking is detected in non-smoking rooms.

Disputes: Any disputes regarding damage charges should be raised with management before check-out.",
            'is_active' => true,
        ]);

        // Special Requests Policy
        HotelPolicy::create([
            'hotel_id' => $hotel->id,
            'policy_type' => 'special_requests',
            'title' => 'Special Requests & Services',
            'description' => "Special Requests: We will do our best to accommodate all special requests such as specific room locations, floor preferences, or bedding configurations. Please note that special requests cannot be guaranteed and are subject to availability at check-in.

How to Request: Special requests should be made at the time of booking or at least 48 hours before arrival. Requests can be submitted through our website, email, or by calling our reservations team.

Room Preferences:
- Ocean view, garden view, or beach view rooms
- Higher or lower floors
- Connecting rooms for families
- Accessible rooms for guests with disabilities

Bedding Preferences:
- King, queen, or twin bed configurations
- Extra pillows or specific pillow types
- Hypoallergenic bedding

Dietary Requirements: Please inform us of any dietary restrictions or allergies at least 24 hours before dining. Our chefs can accommodate most dietary needs including vegetarian, vegan, halal, and gluten-free options.

Special Occasions: Celebrating something special? Let us know! We offer complimentary room decoration for honeymoons, anniversaries, and birthdays when notified in advance.

Airport Transfer: Private speedboat transfers from Male Airport can be arranged at MVR 3,500 per person (round trip). Please book at least 48 hours in advance.

Activities & Excursions: We can arrange various water sports, island hopping tours, diving trips, and cultural excursions. Please inquire at the front desk or concierge.

Additional Services Available:
- In-room dining (6:00 AM - 11:00 PM)
- Laundry and dry cleaning
- Spa and wellness treatments
- Private dining experiences
- Water sports equipment rental",
            'is_active' => true,
        ]);

        $this->command->info('Successfully seeded ' . HotelPolicy::where('hotel_id', $hotel->id)->count() . ' policies for Paradise Bay Hotel!');
    }
}
