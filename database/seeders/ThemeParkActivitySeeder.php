<?php

namespace Database\Seeders;

use App\Models\ThemeParkZone;
use App\Models\ThemeParkActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeParkActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all zones
        $adventureZone = ThemeParkZone::where('zone_type', 'Adventure')->first();
        $waterPark = ThemeParkZone::where('zone_type', 'Water Park')->first();
        $entertainmentZone = ThemeParkZone::where('zone_type', 'Entertainment')->first();
        $rideZone = ThemeParkZone::where('zone_type', 'Rides')->first();

        if (!$adventureZone || !$waterPark || !$entertainmentZone || !$rideZone) {
            $this->command->warn('Theme park zones not found. Please run ThemeParkZoneSeeder first.');
            return;
        }

        // Adventure Zone Activities (Continuous)
        $adventureActivities = [
            [
                'name' => 'Zip Line',
                'description' => 'Soar through the air on our thrilling zip line course',
                'activity_type' => 'continuous',
                'credit_cost' => 8,
                'duration_minutes' => 20,
                'capacity' => 4,
                'min_age' => 12,
                'height_requirement_cm' => 140,
            ],
            [
                'name' => 'Rock Climbing Wall',
                'description' => 'Scale our 15-meter climbing wall with various difficulty levels',
                'activity_type' => 'continuous',
                'credit_cost' => 6,
                'duration_minutes' => 30,
                'capacity' => 8,
                'min_age' => 10,
                'height_requirement_cm' => 130,
            ],
            [
                'name' => 'Rope Course',
                'description' => 'Navigate through challenging obstacles suspended in the air',
                'activity_type' => 'continuous',
                'credit_cost' => 10,
                'duration_minutes' => 45,
                'capacity' => 6,
                'min_age' => 14,
                'height_requirement_cm' => 150,
            ],
            [
                'name' => 'Bungee Jump',
                'description' => 'Experience the ultimate free fall from 50 meters',
                'activity_type' => 'continuous',
                'credit_cost' => 15,
                'duration_minutes' => 15,
                'capacity' => 2,
                'min_age' => 18,
                'height_requirement_cm' => 150,
            ],
            [
                'name' => 'Obstacle Course',
                'description' => 'Test your agility through our adventurous obstacle course',
                'activity_type' => 'continuous',
                'credit_cost' => 5,
                'duration_minutes' => 30,
                'capacity' => 12,
                'min_age' => 8,
                'height_requirement_cm' => null,
            ],
        ];

        // Water Park Activities (Continuous)
        $waterParkActivities = [
            [
                'name' => 'Wave Pool',
                'description' => 'Enjoy the thrill of artificial waves in our massive pool',
                'activity_type' => 'continuous',
                'credit_cost' => 4,
                'duration_minutes' => 60,
                'capacity' => 100,
                'min_age' => 5,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Lazy River',
                'description' => 'Relax on a float around our scenic lazy river',
                'activity_type' => 'continuous',
                'credit_cost' => 3,
                'duration_minutes' => 45,
                'capacity' => 50,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Speed Slides',
                'description' => 'Race down our high-speed water slides',
                'activity_type' => 'continuous',
                'credit_cost' => 6,
                'duration_minutes' => 20,
                'capacity' => 10,
                'min_age' => 12,
                'height_requirement_cm' => 140,
            ],
            [
                'name' => 'Family Raft Ride',
                'description' => 'Splash down together on our family-friendly raft ride',
                'activity_type' => 'continuous',
                'credit_cost' => 5,
                'duration_minutes' => 25,
                'capacity' => 16,
                'min_age' => 6,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Kids Splash Zone',
                'description' => 'Interactive water playground for younger children',
                'activity_type' => 'continuous',
                'credit_cost' => 3,
                'duration_minutes' => 45,
                'capacity' => 30,
                'min_age' => 3,
                'max_age' => 10,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Aqua Aerobics',
                'description' => 'Fun water-based fitness activities',
                'activity_type' => 'continuous',
                'credit_cost' => 4,
                'duration_minutes' => 30,
                'capacity' => 20,
                'min_age' => 16,
                'height_requirement_cm' => null,
            ],
        ];

        // Entertainment Zone Activities (Scheduled Shows)
        $entertainmentActivities = [
            [
                'name' => 'Magic Show',
                'description' => 'Amazing illusions and mind-bending magic tricks',
                'activity_type' => 'scheduled',
                'credit_cost' => 5,
                'duration_minutes' => 45,
                'capacity' => 200,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Musical Performance',
                'description' => 'Live musical concerts featuring local and international artists',
                'activity_type' => 'scheduled',
                'credit_cost' => 8,
                'duration_minutes' => 60,
                'capacity' => 300,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Acrobatic Show',
                'description' => 'Breathtaking acrobatic performances and stunts',
                'activity_type' => 'scheduled',
                'credit_cost' => 6,
                'duration_minutes' => 40,
                'capacity' => 250,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Cultural Dance',
                'description' => 'Traditional Maldivian dance and cultural performances',
                'activity_type' => 'scheduled',
                'credit_cost' => 4,
                'duration_minutes' => 35,
                'capacity' => 200,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Comedy Show',
                'description' => 'Stand-up comedy and hilarious entertainment',
                'activity_type' => 'scheduled',
                'credit_cost' => 5,
                'duration_minutes' => 50,
                'capacity' => 150,
                'min_age' => 13,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Fire Show',
                'description' => 'Spectacular fire dancing and pyrotechnic display (evening only)',
                'activity_type' => 'scheduled',
                'credit_cost' => 8,
                'duration_minutes' => 30,
                'capacity' => 300,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
        ];

        // Ride Zone Activities (Continuous)
        $rideActivities = [
            [
                'name' => 'Roller Coaster',
                'description' => 'Heart-pounding loops and drops on our signature coaster',
                'activity_type' => 'continuous',
                'credit_cost' => 12,
                'duration_minutes' => 5,
                'capacity' => 24,
                'min_age' => 12,
                'height_requirement_cm' => 140,
            ],
            [
                'name' => 'Ferris Wheel',
                'description' => 'Panoramic views from our giant observation wheel',
                'activity_type' => 'continuous',
                'credit_cost' => 4,
                'duration_minutes' => 15,
                'capacity' => 48,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Carousel',
                'description' => 'Classic carousel ride perfect for families',
                'activity_type' => 'continuous',
                'credit_cost' => 3,
                'duration_minutes' => 8,
                'capacity' => 32,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Bumper Cars',
                'description' => 'Crash and bash in our electric bumper car arena',
                'activity_type' => 'continuous',
                'credit_cost' => 4,
                'duration_minutes' => 10,
                'capacity' => 20,
                'min_age' => 6,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Spinning Teacups',
                'description' => 'Whirl around in colorful giant teacups',
                'activity_type' => 'continuous',
                'credit_cost' => 3,
                'duration_minutes' => 8,
                'capacity' => 24,
                'min_age' => null,
                'height_requirement_cm' => null,
            ],
            [
                'name' => 'Pirate Ship',
                'description' => 'Swing back and forth on our giant pirate ship',
                'activity_type' => 'continuous',
                'credit_cost' => 5,
                'duration_minutes' => 10,
                'capacity' => 40,
                'min_age' => 8,
                'height_requirement_cm' => 120,
            ],
            [
                'name' => 'Drop Tower',
                'description' => 'Free fall from 60 meters in our extreme drop ride',
                'activity_type' => 'continuous',
                'credit_cost' => 10,
                'duration_minutes' => 5,
                'capacity' => 12,
                'min_age' => 14,
                'height_requirement_cm' => 145,
            ],
        ];

        // Create Adventure Zone activities
        $this->createActivities($adventureZone->id, $adventureActivities, 'Adventure Zone');

        // Create Water Park activities
        $this->createActivities($waterPark->id, $waterParkActivities, 'Water Park');

        // Create Entertainment Zone activities
        $this->createActivities($entertainmentZone->id, $entertainmentActivities, 'Entertainment Zone');

        // Create Ride Zone activities
        $this->createActivities($rideZone->id, $rideActivities, 'Ride Zone');

        $this->command->info('✓ Theme park activities seeded successfully!');
        $this->command->info('  - Adventure Zone: 5 activities');
        $this->command->info('  - Water Park: 6 activities');
        $this->command->info('  - Entertainment Zone: 6 shows');
        $this->command->info('  - Ride Zone: 7 rides');
        $this->command->info('  Total: 24 activities');
        $this->command->info('✓ All activities assigned to zone staff');
    }

    private function createActivities(int $zoneId, array $activities, string $zoneName): void
    {
        // Get theme park staff to assign to activities
        $themeParkStaff = \App\Models\Staff::where('role', \App\Enums\StaffRole::THEME_PARK_STAFF)
            ->where('is_active', true)
            ->get();

        foreach ($activities as $index => $activityData) {
            // Assign staff in round-robin fashion if available
            $assignedStaffId = null;
            if ($themeParkStaff->isNotEmpty()) {
                $assignedStaffId = $themeParkStaff[$index % $themeParkStaff->count()]->id;
            }

            ThemeParkActivity::create([
                'theme_park_zone_id' => $zoneId,
                'assigned_staff_id' => $assignedStaffId,
                'name' => $activityData['name'],
                'description' => $activityData['description'],
                'activity_type' => $activityData['activity_type'],
                'credit_cost' => $activityData['credit_cost'],
                'duration_minutes' => $activityData['duration_minutes'],
                'capacity' => $activityData['capacity'],
                'operating_hours_start' => '09:00:00',
                'operating_hours_end' => '21:00:00',
                'min_age' => $activityData['min_age'] ?? null,
                'max_age' => $activityData['max_age'] ?? null,
                'height_requirement_cm' => $activityData['height_requirement_cm'] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
