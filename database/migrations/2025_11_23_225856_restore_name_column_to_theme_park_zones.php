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
        Schema::table('theme_park_zones', function (Blueprint $table) {
            // Remove unique constraint from zone_type
            $table->dropUnique(['zone_type']);

            // Add name column back (zones can have specific names like "Adventure Zone 1")
            $table->string('name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_zones', function (Blueprint $table) {
            // Remove name column
            $table->dropColumn('name');

            // Add unique constraint back to zone_type
            $table->unique('zone_type');
        });
    }
};
