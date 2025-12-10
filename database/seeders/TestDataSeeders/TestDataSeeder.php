<?php

namespace Database\Seeders\TestDataSeeders;

use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\ThemeParkZone;
use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivitySchedule;
use App\Models\ThemeParkSetting;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test hotel bookings...');
        $this->createHotelBookings();

        $this->command->info('Creating theme park settings...');
        $this->createThemeParkSettings();

        $this->command->info('Creating theme park activities...');
        $this->createThemeParkActivities();

        $this->command->info('Creating theme park schedules...');
        $this->createThemeParkSchedules();

        $this->command->info('✓ Test data created successfully!');
    }

    /**
     * Create test hotel bookings with checked-in status
     */
    private function createHotelBookings(): void
    {
        $hotel = Hotel::where('name', 'Paradise Bay Hotel')->first();
        $guests = Guest::all();

        if (!$hotel || $guests->isEmpty()) {
            $this->command->warn('Hotel or guests not found. Skipping bookings.');
            return;
        }

        // Get some rooms
        $rooms = Room::where('hotel_id', $hotel->id)->limit(3)->get();

        if ($rooms->isEmpty()) {
            $this->command->warn('No rooms found. Skipping bookings.');
            return;
        }

        // Booking 1: Checked-in guest (for testing theme park access)
        HotelBooking::create([
            'hotel_id' => $hotel->id,
            'room_id' => $rooms[0]->id,
            'guest_id' => $guests[0]->id,
            'check_in_date' => Carbon::today(),
            'check_out_date' => Carbon::today()->addDays(3),
            'number_of_guests' => 2,
            'number_of_rooms' => 1,
            'status' => 'checked_in',
            'total_price' => 3000.00,
            'payment_status' => 'paid',
            'payment_method' => 'credit_card',
            'booking_reference' => 'BK-TEST001',
            'checked_in_at' => Carbon::now(),
            'checked_in_by' => Staff::where('role', 'hotel_manager')->first()?->id,
        ]);

        // Booking 2: Confirmed booking (future check-in)
        if ($guests->count() > 1 && $rooms->count() > 1) {
            HotelBooking::create([
                'hotel_id' => $hotel->id,
                'room_id' => $rooms[1]->id,
                'guest_id' => $guests[1]->id,
                'check_in_date' => Carbon::today()->addDays(2),
                'check_out_date' => Carbon::today()->addDays(5),
                'number_of_guests' => 2,
                'number_of_rooms' => 1,
                'status' => 'confirmed',
                'total_price' => 4500.00,
                'payment_status' => 'paid',
                'payment_method' => 'debit_card',
                'booking_reference' => 'BK-TEST002',
            ]);
        }

        // Booking 3: Another checked-in guest
        if ($guests->count() > 1 && $rooms->count() > 2) {
            HotelBooking::create([
                'hotel_id' => $hotel->id,
                'room_id' => $rooms[2]->id,
                'guest_id' => $guests[1]->id,
                'check_in_date' => Carbon::yesterday(),
                'check_out_date' => Carbon::today()->addDays(4),
                'number_of_guests' => 3,
                'number_of_rooms' => 1,
                'status' => 'checked_in',
                'total_price' => 5000.00,
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'booking_reference' => 'BK-TEST003',
                'checked_in_at' => Carbon::yesterday()->setTime(14, 0),
                'checked_in_by' => Staff::where('role', 'hotel_manager')->first()?->id,
            ]);
        }
    }

    /**
     * Create theme park settings if not exist
     */
    private function createThemeParkSettings(): void
    {
        if (ThemeParkSetting::count() === 0) {
            // Get theme park manager to set as updater
            $themeParkManager = Staff::where('role', 'theme_park_manager')->first();

            if ($themeParkManager) {
                ThemeParkSetting::setCreditPrice(5.00, $themeParkManager->id);
            }
        }
    }

    /**
     * Create diverse theme park activities
     */
    private function createThemeParkActivities(): void
    {
        $zones = ThemeParkZone::all();
        $themeParkStaff = Staff::where('role', 'theme_park_staff')->first();

        if ($zones->isEmpty()) {
            $this->command->warn('No zones found. Skipping activities.');
            return;
        }

        $activities = [
            // Adventure Zone activities
            ['name' => 'Rock Climbing Wall', 'zone_type' => 'Adventure', 'ticket_cost' => 3, 'duration' => 30, 'capacity' => 10, 'min_age' => 8],
            ['name' => 'Zipline Adventure', 'zone_type' => 'Adventure', 'ticket_cost' => 5, 'duration' => 20, 'capacity' => 15, 'min_age' => 10, 'height_req' => 140],
            ['name' => 'Obstacle Course', 'zone_type' => 'Adventure', 'ticket_cost' => 4, 'duration' => 45, 'capacity' => 20, 'min_age' => 12],

            // Water Park activities
            ['name' => 'Giant Water Slide', 'zone_type' => 'Water Park', 'ticket_cost' => 2, 'duration' => 15, 'capacity' => 30, 'min_age' => 6, 'height_req' => 120],
            ['name' => 'Wave Pool', 'zone_type' => 'Water Park', 'ticket_cost' => 3, 'duration' => 60, 'capacity' => 50, 'min_age' => null],
            ['name' => 'Lazy River', 'zone_type' => 'Water Park', 'ticket_cost' => 2, 'duration' => 30, 'capacity' => 40, 'min_age' => null],

            // Kids Area activities
            ['name' => 'Mini Carousel', 'zone_type' => 'Kids Area', 'ticket_cost' => 1, 'duration' => 10, 'capacity' => 12, 'min_age' => 3, 'max_age' => 10],
            ['name' => 'Soft Play Zone', 'zone_type' => 'Kids Area', 'ticket_cost' => 2, 'duration' => 45, 'capacity' => 30, 'min_age' => 2, 'max_age' => 8],
            ['name' => 'Mini Train Ride', 'zone_type' => 'Kids Area', 'ticket_cost' => 1, 'duration' => 15, 'capacity' => 20, 'min_age' => 2, 'max_age' => 12],

            // Entertainment activities
            ['name' => 'Magic Show', 'zone_type' => 'Entertainment', 'ticket_cost' => 4, 'duration' => 60, 'capacity' => 100, 'min_age' => null],
            ['name' => 'Dolphin Performance', 'zone_type' => 'Entertainment', 'ticket_cost' => 6, 'duration' => 45, 'capacity' => 80, 'min_age' => null],
            ['name' => 'Musical Fountain Show', 'zone_type' => 'Entertainment', 'ticket_cost' => 2, 'duration' => 30, 'capacity' => 150, 'min_age' => null],

            // Thrill Rides activities
            ['name' => 'Mega Roller Coaster', 'zone_type' => 'Thrill Rides', 'ticket_cost' => 7, 'duration' => 5, 'capacity' => 24, 'min_age' => 14, 'height_req' => 150],
            ['name' => 'Free Fall Tower', 'zone_type' => 'Thrill Rides', 'ticket_cost' => 6, 'duration' => 3, 'capacity' => 16, 'min_age' => 13, 'height_req' => 145],
            ['name' => 'Spinning Coaster', 'zone_type' => 'Thrill Rides', 'ticket_cost' => 5, 'duration' => 4, 'capacity' => 20, 'min_age' => 12, 'height_req' => 140],

            // Family activities
            ['name' => 'Ferris Wheel', 'zone_type' => 'Family', 'ticket_cost' => 3, 'duration' => 15, 'capacity' => 32, 'min_age' => null],
            ['name' => 'Bumper Cars', 'zone_type' => 'Family', 'ticket_cost' => 2, 'duration' => 10, 'capacity' => 16, 'min_age' => 5],
            ['name' => 'Family Boat Ride', 'zone_type' => 'Family', 'ticket_cost' => 3, 'duration' => 20, 'capacity' => 24, 'min_age' => null],
        ];

        foreach ($activities as $activityData) {
            $zone = $zones->where('zone_type', $activityData['zone_type'])->first();

            if (!$zone) {
                continue;
            }

            ThemeParkActivity::create([
                'theme_park_zone_id' => $zone->id,
                'name' => $activityData['name'],
                'description' => 'Experience the thrill of ' . $activityData['name'] . ' in our ' . $zone->name . '!',
                'ticket_cost' => $activityData['ticket_cost'],
                'capacity_per_session' => $activityData['capacity'],
                'duration_minutes' => $activityData['duration'],
                'min_age' => $activityData['min_age'] ?? null,
                'max_age' => $activityData['max_age'] ?? null,
                'height_requirement_cm' => $activityData['height_req'] ?? null,
                'assigned_staff_id' => $themeParkStaff?->id,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Create schedules for theme park activities
     */
    private function createThemeParkSchedules(): void
    {
        $activities = ThemeParkActivity::all();

        if ($activities->isEmpty()) {
            $this->command->warn('No activities found. Skipping schedules.');
            return;
        }

        // Create schedules for the next 7 days
        foreach ($activities as $activity) {
            for ($day = 0; $day < 7; $day++) {
                $date = Carbon::today()->addDays($day);

                // Morning session
                ThemeParkActivitySchedule::create([
                    'activity_id' => $activity->id,
                    'schedule_date' => $date,
                    'start_time' => '09:00:00',
                    'end_time' => '12:00:00',
                    'available_slots' => $activity->capacity_per_session,
                    'booked_slots' => 0,
                ]);

                // Afternoon session
                ThemeParkActivitySchedule::create([
                    'activity_id' => $activity->id,
                    'schedule_date' => $date,
                    'start_time' => '13:00:00',
                    'end_time' => '17:00:00',
                    'available_slots' => $activity->capacity_per_session,
                    'booked_slots' => 0,
                ]);

                // Evening session (for entertainment shows)
                if (in_array($activity->name, ['Magic Show', 'Dolphin Performance', 'Musical Fountain Show'])) {
                    ThemeParkActivitySchedule::create([
                        'activity_id' => $activity->id,
                        'schedule_date' => $date,
                        'start_time' => '18:00:00',
                        'end_time' => '20:00:00',
                        'available_slots' => $activity->capacity_per_session,
                        'booked_slots' => 0,
                    ]);
                }
            }
        }
    }
}
