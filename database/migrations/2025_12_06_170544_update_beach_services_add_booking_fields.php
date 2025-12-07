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
        Schema::table('beach_services', function (Blueprint $table) {
            // Link to category (new parent-child relationship)
            $table->foreignId('beach_activity_category_id')
                ->after('id')
                ->constrained('beach_activity_categories')
                ->cascadeOnDelete();

            // Booking type: fixed_slot (volleyball court) or flexible_duration (jetski)
            $table->enum('booking_type', ['fixed_slot', 'flexible_duration'])
                ->default('fixed_slot')
                ->after('description');

            // For fixed_slot: slot duration and price per slot
            $table->integer('slot_duration_minutes')->nullable()->after('booking_type'); // e.g., 60, 90, 120
            $table->decimal('slot_price', 10, 2)->nullable()->after('slot_duration_minutes');

            // For flexible_duration: price per hour
            $table->decimal('price_per_hour', 10, 2)->nullable()->after('slot_price');

            // Concurrent capacity (how many overlapping bookings allowed)
            $table->integer('concurrent_capacity')->default(1)->after('capacity_limit');
            // Note: capacity_limit = total slots/equipment, concurrent_capacity = simultaneous bookings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beach_services', function (Blueprint $table) {
            // Drop columns in reverse order
            $table->dropColumn('concurrent_capacity');
            $table->dropColumn('price_per_hour');
            $table->dropColumn('slot_price');
            $table->dropColumn('slot_duration_minutes');
            $table->dropColumn('booking_type');
            $table->dropForeign(['beach_activity_category_id']);
            $table->dropColumn('beach_activity_category_id');
        });
    }
};
