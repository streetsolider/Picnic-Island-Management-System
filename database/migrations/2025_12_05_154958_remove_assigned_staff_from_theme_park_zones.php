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
            // Drop foreign key constraint first
            $table->dropForeign(['assigned_staff_id']);

            // Drop the column
            $table->dropColumn('assigned_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_zones', function (Blueprint $table) {
            // Add column back
            $table->foreignId('assigned_staff_id')
                  ->nullable()
                  ->after('closing_time')
                  ->constrained('staff')
                  ->nullOnDelete();
        });
    }
};
