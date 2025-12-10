<?php

namespace App\Console\Commands;

use App\Models\Gallery;
use App\Models\Hotel;
use Illuminate\Console\Command;

class CreateHotelGalleries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotels:create-galleries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create hotel galleries for existing hotels that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating hotel galleries for existing hotels...');

        $hotels = Hotel::all();
        $created = 0;
        $skipped = 0;

        foreach ($hotels as $hotel) {
            // Check if hotel already has a hotel gallery
            if ($hotel->hotelGallery) {
                $this->line("Skipped: {$hotel->name} (already has a hotel gallery)");
                $skipped++;
                continue;
            }

            // Create hotel gallery
            Gallery::create([
                'hotel_id' => $hotel->id,
                'name' => $hotel->name . ' Gallery',
                'description' => 'Main gallery for ' . $hotel->name,
                'type' => Gallery::TYPE_HOTEL,
            ]);

            $this->info("Created hotel gallery for: {$hotel->name}");
            $created++;
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Created: {$created} hotel galleries");
        $this->info("- Skipped: {$skipped} hotels (already have galleries)");
        $this->info("- Total hotels: {$hotels->count()}");
    }
}
