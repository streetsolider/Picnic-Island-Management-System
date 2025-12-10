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
        Schema::create('booking_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                  ->constrained('hotel_bookings')
                  ->onDelete('cascade');
            $table->enum('guest_type', ['primary', 'additional'])->default('additional');
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('relationship_to_primary', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->timestamps();

            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_guests');
    }
};
