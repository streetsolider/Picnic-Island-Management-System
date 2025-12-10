<?php

namespace Database\Seeders;

use App\Models\Ferry\FerryRoute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FerryRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            // Hulhumale <-> Picnic Island
            [
                'origin' => 'Hulhumale',
                'destination' => 'Picnic Island',
                'is_active' => true,
            ],
            [
                'origin' => 'Picnic Island',
                'destination' => 'Hulhumale',
                'is_active' => true,
            ],
            // Male <-> Picnic Island
            [
                'origin' => 'Male',
                'destination' => 'Picnic Island',
                'is_active' => true,
            ],
            [
                'origin' => 'Picnic Island',
                'destination' => 'Male',
                'is_active' => true,
            ],
        ];

        foreach ($routes as $routeData) {
            FerryRoute::create($routeData);
        }
    }
}
