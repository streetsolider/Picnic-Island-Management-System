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
        Schema::create('beach_service_bookings', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->foreignId('beach_service_id')->constrained('beach_services')->cascadeOnDelete();
            $table->foreignId('hotel_booking_id')->constrained('hotel_bookings')->cascadeOnDelete(); // REQUIRED

            // Booking reference (auto-generated)
            $table->string('booking_reference', 20)->unique(); // Format: BSB-XXXXXXXX

            // Date and time
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_hours')->nullable(); // For flexible duration

            // Pricing
            $table->decimal('price_per_unit', 10, 2); // slot_price or price_per_hour
            $table->decimal('total_price', 10, 2);

            // Status tracking
            $table->enum('status', ['confirmed', 'redeemed', 'cancelled', 'expired'])->default('confirmed');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('paid');

            // Redemption tracking
            $table->foreignId('redeemed_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('redeemed_at')->nullable();

            // Cancellation
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('booking_reference');
            $table->index(['beach_service_id', 'booking_date']);
            $table->index(['guest_id', 'status']);
            $table->index(['booking_date', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beach_service_bookings');
    }
};
