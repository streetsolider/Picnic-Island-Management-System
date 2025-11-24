<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename beach_areas table to beach_services
        Schema::rename('beach_areas', 'beach_services');

        // Update the table structure to reflect service types
        Schema::table('beach_services', function (Blueprint $table) {
            // Rename 'location' column to 'service_type' to store Excursions, Water Sports, etc.
            $table->renameColumn('location', 'service_type');
        });

        // Add a comment about service types
        // Service types: Excursions, Water Sports, Beach Sports, Beach Huts
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the column rename
        Schema::table('beach_services', function (Blueprint $table) {
            $table->renameColumn('service_type', 'location');
        });

        // Rename table back
        Schema::rename('beach_services', 'beach_areas');
    }
};
