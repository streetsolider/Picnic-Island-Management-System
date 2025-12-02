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
        Schema::create('ferry_tickets', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hotel_booking_id')->constrained()->onDelete('cascade'); // CRITICAL
            $table->foreignId('ferry_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('ferry_route_id')->constrained()->onDelete('cascade');
            $table->foreignId('ferry_vessel_id')->constrained()->onDelete('cascade');

            // Ticket Details
            $table->string('ticket_reference')->unique(); // Format: FT-XXXXXXXX
            $table->date('travel_date');
            $table->integer('number_of_passengers');

            // Pricing
            $table->decimal('price_per_passenger', 10, 2);
            $table->decimal('total_price', 10, 2);

            // Status
            $table->enum('status', ['confirmed', 'cancelled', 'used', 'expired'])->default('confirmed');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('paid');
            $table->string('payment_method')->nullable();

            // Validation
            $table->foreignId('validated_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();

            // Cancellation
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            // Indexes for better query performance
            $table->index(['guest_id', 'status']);
            $table->index(['ferry_schedule_id', 'travel_date']);
            $table->index('ticket_reference');
            $table->index('hotel_booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_tickets');
    }
};
