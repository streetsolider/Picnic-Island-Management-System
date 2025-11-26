<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ferry_vessels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ferry_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Mainland to Island"
            $table->string('origin');
            $table->string('destination');
            $table->integer('duration_minutes');
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ferry_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ferry_route_id')->constrained()->onDelete('cascade');
            $table->foreignId('ferry_vessel_id')->constrained()->onDelete('cascade');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->json('days_of_week'); // ["Monday", "Wednesday", "Friday"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_schedules');
        Schema::dropIfExists('ferry_routes');
        Schema::dropIfExists('ferry_vessels');
    }
};
