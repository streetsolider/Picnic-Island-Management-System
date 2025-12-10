<?php

namespace Database\Seeders;

use App\Models\Ferry\FerryVessel;
use App\Models\Staff;
use App\Enums\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FerryVesselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get ferry operators if any exist
        $ferryOperators = Staff::where('role', StaffRole::FERRY_OPERATOR)
            ->where('is_active', true)
            ->get();

        $vessels = [
            // 2 Ferries with 50 passenger capacity
            [
                'name' => 'Island Express',
                'registration_number' => 'FRY-001-PI',
                'vessel_type' => 'Ferry',
                'capacity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Ocean Breeze',
                'registration_number' => 'FRY-002-PI',
                'vessel_type' => 'Ferry',
                'capacity' => 50,
                'is_active' => true,
            ],
            // 2 Speed Boats with 30 people capacity
            [
                'name' => 'Swift Voyager',
                'registration_number' => 'SPB-001-PI',
                'vessel_type' => 'Speed Boat',
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Sea Runner',
                'registration_number' => 'SPB-002-PI',
                'vessel_type' => 'Speed Boat',
                'capacity' => 30,
                'is_active' => true,
            ],
            // 1 Boat with 100 person capacity
            [
                'name' => 'Paradise Cruiser',
                'registration_number' => 'BOT-001-PI',
                'vessel_type' => 'Boat',
                'capacity' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($vessels as $index => $vesselData) {
            // Assign operator if available (round-robin)
            if ($ferryOperators->isNotEmpty()) {
                $vesselData['operator_id'] = $ferryOperators[$index % $ferryOperators->count()]->id;
            }

            FerryVessel::create($vesselData);
        }
    }
}
