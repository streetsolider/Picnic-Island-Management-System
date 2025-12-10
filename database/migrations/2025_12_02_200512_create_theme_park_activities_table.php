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
        Schema::create('theme_park_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_park_zone_id')->constrained('theme_park_zones')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('ticket_cost'); // Number of tickets required
            $table->unsignedInteger('capacity_per_session')->default(50);
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->unsignedInteger('min_age')->nullable();
            $table->unsignedInteger('max_age')->nullable();
            $table->unsignedInteger('height_requirement_cm')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('theme_park_zone_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_park_activities');
    }
};
