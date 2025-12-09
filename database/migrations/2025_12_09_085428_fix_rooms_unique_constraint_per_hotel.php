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
        Schema::table('rooms', function (Blueprint $table) {
            // Drop the global unique constraint on room_number
            $table->dropUnique('rooms_room_number_unique');

            // Add compound unique constraint: room numbers unique per hotel
            $table->unique(['hotel_id', 'room_number'], 'rooms_hotel_id_room_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Drop the compound unique constraint
            $table->dropUnique('rooms_hotel_id_room_number_unique');

            // Re-add the global unique constraint
            $table->unique('room_number', 'rooms_room_number_unique');
        });
    }
};
