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
        // Create new table for scheduled shows only
        Schema::create('theme_park_show_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                  ->constrained('theme_park_activities')
                  ->onDelete('cascade')
                  ->comment('FK to activities where activity_type = scheduled');
            $table->date('show_date');
            $table->time('show_time');
            $table->unsignedInteger('venue_capacity')
                  ->comment('Capacity for this specific show instance');
            $table->unsignedInteger('tickets_sold')->default(0);
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled');
            $table->timestamps();

            // Indexes
            $table->index('activity_id');
            $table->index('show_date');
            $table->index('status');

            // Unique constraint: one show per activity per date/time
            $table->unique(['activity_id', 'show_date', 'show_time'], 'show_schedule_unique');
        });

        // Drop old activity schedules table (it's empty anyway)
        Schema::dropIfExists('theme_park_activity_schedules');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate old table
        Schema::create('theme_park_activity_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('theme_park_activities')->onDelete('cascade');
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('available_slots');
            $table->unsignedInteger('booked_slots')->default(0);
            $table->timestamps();

            $table->index('activity_id');
            $table->index('schedule_date');
            $table->unique(['activity_id', 'schedule_date', 'start_time'], 'tp_activity_schedule_unique');
        });

        // Drop new table
        Schema::dropIfExists('theme_park_show_schedules');
    }
};
