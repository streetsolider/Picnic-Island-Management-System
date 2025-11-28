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
        Schema::create('room_type_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->enum('room_type', ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family']);
            $table->decimal('base_price', 10, 2);
            $table->string('currency', 3)->default('MVR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Each room type should have only one pricing per hotel
            $table->unique(['hotel_id', 'room_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_pricing');
    }
};
