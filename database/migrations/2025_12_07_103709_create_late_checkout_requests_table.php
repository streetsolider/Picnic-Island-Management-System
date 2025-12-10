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
        Schema::create('late_checkout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_booking_id')->constrained('hotel_bookings')->cascadeOnDelete();
            $table->time('requested_checkout_time'); // Time guest wants to checkout (max 18:00:00)
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('guest_notes')->nullable(); // Guest's reason for request

            // Manager response fields
            $table->foreignId('reviewed_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('manager_notes')->nullable(); // Manager's notes/reason for decision

            // Next booking information (captured at time of request for manager's reference)
            $table->boolean('has_next_booking')->default(false);
            $table->json('next_booking_info')->nullable(); // {check_in_time, guest_name, room_type, etc.}

            $table->timestamps();

            // Indexes
            $table->index(['hotel_booking_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_checkout_requests');
    }
};
