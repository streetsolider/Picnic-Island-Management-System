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
        Schema::create('promotional_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();

            // Promotion details
            $table->string('promotion_name'); // e.g., "Family Fun Package", "Local Resident Special"
            $table->text('promotion_description')->nullable(); // Shown to customers

            // Discount configuration
            $table->enum('discount_type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('discount_value', 10, 2);

            // Promotion validity period
            $table->date('start_date')->nullable(); // null = always active
            $table->date('end_date')->nullable(); // null = no end date

            // Booking conditions (all optional - null means no restriction)
            $table->integer('minimum_rooms')->nullable(); // e.g., 2 for family packages
            $table->integer('maximum_rooms')->nullable(); // e.g., max 5 rooms per booking
            $table->integer('minimum_nights')->nullable(); // e.g., 2 for weekend packages
            $table->integer('maximum_nights')->nullable(); // e.g., 3 for quick getaway
            $table->integer('booking_advance_days')->nullable(); // e.g., 30 for early bird

            // Room type restrictions
            $table->json('applicable_room_types')->nullable(); // null = all room types, or ["Deluxe", "Suite"]

            // Promo code for targeted marketing
            $table->string('promo_code')->nullable()->unique(); // e.g., "LOCAL2025", null = auto-apply

            // Status and priority
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Higher priority wins if multiple match

            $table->timestamps();

            // Indexes
            $table->index('hotel_id');
            $table->index(['start_date', 'end_date']);
            $table->index('promo_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_discounts');
    }
};
