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
            // Use custom short constraint name to avoid "Identifier name too long" error
            $table->unique(['activity_id', 'schedule_date', 'start_time'], 'tp_activity_schedule_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_park_activity_schedules');
    }
};
