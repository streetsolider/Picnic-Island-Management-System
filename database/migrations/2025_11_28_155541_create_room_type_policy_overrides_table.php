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
        Schema::create('room_type_policy_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->enum('room_type', ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family']);
            $table->enum('policy_type', [
                'cancellation',
                'check_in_out',
                'payment',
                'house_rules',
                'age_restriction',
                'damage_deposit',
                'special_requests'
            ]);
            $table->string('title');
            $table->text('description');
            $table->timestamps();

            // Ensure one override per policy type per room type per hotel
            $table->unique(['hotel_id', 'room_type', 'policy_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_policy_overrides');
    }
};
