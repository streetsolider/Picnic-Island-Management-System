<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeachActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Water Sports',
                'description' => 'Exciting water-based activities including jet skiing, catamarans, and more',
                'icon' => '🏄',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beach Activities',
                'description' => 'Beach sports and recreational activities including volleyball, basketball, and football',
                'icon' => '🏐',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beach Huts',
                'description' => 'Private beach huts for relaxation, cooking, and BBQ activities',
                'icon' => '🏖️',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('beach_activity_categories')->insert($categories);

        $this->command->info('Beach activity categories seeded successfully!');
    }
}
