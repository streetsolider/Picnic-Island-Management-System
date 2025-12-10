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
        Schema::create('room_reassignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                  ->constrained('hotel_bookings')
                  ->onDelete('cascade');
            $table->foreignId('old_room_id')->constrained('rooms');
            $table->foreignId('new_room_id')->constrained('rooms');
            $table->foreignId('reassigned_by')->constrained('staff');
            $table->text('reason');
            $table->timestamp('reassigned_at');
            $table->timestamps();

            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_reassignments');
    }
};
