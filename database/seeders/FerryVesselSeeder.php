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
            [
                'name' => 'Island Express',
                'registration_number' => 'FRY-001-PI',
                'vessel_type' => 'Ferry',
                'capacity' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Ocean Breeze',
                'registration_number' => 'FRY-002-PI',
                'vessel_type' => 'Ferry',
                'capacity' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Swift Voyager',
                'registration_number' => 'SPB-003-PI',
                'vessel_type' => 'Speed Boat',
                'capacity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Sea Star',
                'registration_number' => 'BOT-004-PI',
                'vessel_type' => 'Boat',
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Paradise Cruiser',
                'registration_number' => 'FRY-005-PI',
                'vessel_type' => 'Ferry',
                'capacity' => 180,
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
