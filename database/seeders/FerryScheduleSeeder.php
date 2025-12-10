<?php

namespace Database\Seeders;

use App\Models\Ferry\FerryRoute;
use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FerryScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all routes and vessels
        $routes = FerryRoute::all();
        $vessels = FerryVessel::where('is_active', true)->get();

        if ($routes->isEmpty() || $vessels->isEmpty()) {
            $this->command->warn('Ferry routes or vessels not found. Please run FerryRouteSeeder and FerryVesselSeeder first.');
            return;
        }

        // All days of the week
        $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        // Define schedules for each route type
        $scheduleTemplates = [
            // Hulhumale -> Picnic Island (45 minutes)
            'Hulhumale → Picnic Island' => [
                ['departure' => '06:00:00', 'arrival' => '06:45:00'], // Morning
                ['departure' => '12:00:00', 'arrival' => '12:45:00'], // Afternoon
                ['departure' => '17:00:00', 'arrival' => '17:45:00'], // Evening
                ['departure' => '21:00:00', 'arrival' => '21:45:00'], // Night
            ],
            // Picnic Island -> Hulhumale (45 minutes)
            'Picnic Island → Hulhumale' => [
                ['departure' => '07:00:00', 'arrival' => '07:45:00'], // Morning
                ['departure' => '13:00:00', 'arrival' => '13:45:00'], // Afternoon
                ['departure' => '18:00:00', 'arrival' => '18:45:00'], // Evening
                ['departure' => '22:00:00', 'arrival' => '22:45:00'], // Night
            ],
            // Male -> Picnic Island (60 minutes)
            'Male → Picnic Island' => [
                ['departure' => '06:30:00', 'arrival' => '07:30:00'], // Morning
                ['departure' => '12:30:00', 'arrival' => '13:30:00'], // Afternoon
                ['departure' => '17:30:00', 'arrival' => '18:30:00'], // Evening
                ['departure' => '21:30:00', 'arrival' => '22:30:00'], // Night
            ],
            // Picnic Island -> Male (60 minutes)
            'Picnic Island → Male' => [
                ['departure' => '08:00:00', 'arrival' => '09:00:00'], // Morning
                ['departure' => '14:00:00', 'arrival' => '15:00:00'], // Afternoon
                ['departure' => '19:00:00', 'arrival' => '20:00:00'], // Evening
                ['departure' => '23:00:00', 'arrival' => '00:00:00'], // Night
            ],
        ];

        $vesselIndex = 0;
        $scheduleCount = 0;

        // Create schedules for each route
        foreach ($routes as $route) {
            $routeName = $route->name; // Uses the name attribute from the model

            if (!isset($scheduleTemplates[$routeName])) {
                continue;
            }

            foreach ($scheduleTemplates[$routeName] as $schedule) {
                // Assign vessel in round-robin fashion
                $vessel = $vessels[$vesselIndex % $vessels->count()];

                FerrySchedule::create([
                    'ferry_route_id' => $route->id,
                    'ferry_vessel_id' => $vessel->id,
                    'departure_time' => $schedule['departure'],
                    'arrival_time' => $schedule['arrival'],
                    'days_of_week' => $allDays,
                ]);

                $vesselIndex++;
                $scheduleCount++;
            }
        }

        $this->command->info("Created {$scheduleCount} ferry schedules for all routes.");
    }
}
