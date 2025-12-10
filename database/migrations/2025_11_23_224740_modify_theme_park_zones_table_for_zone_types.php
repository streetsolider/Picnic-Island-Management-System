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
            // Remove name column - zone_type itself is now the name
            $table->dropColumn('name');

            // Remove capacity and operating hours - these are now managed by Theme Park Staff
            $table->dropColumn(['capacity_limit', 'opening_time', 'closing_time']);

            // Make zone_type unique since it's now the primary identifier
            $table->unique('zone_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_zones', function (Blueprint $table) {
            // Restore name column
            $table->string('name')->after('id');

            // Restore capacity and operating hours
            $table->integer('capacity_limit')->default(0)->after('description');
            $table->time('opening_time')->nullable()->after('capacity_limit');
            $table->time('closing_time')->nullable()->after('opening_time');

            // Remove unique constraint from zone_type
            $table->dropUnique(['zone_type']);
        });
    }
};
