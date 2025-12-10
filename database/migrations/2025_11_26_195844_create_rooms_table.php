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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('room_number')->unique();
            $table->enum('room_type', ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family']);
            $table->enum('bed_size', ['King', 'Queen', 'Twin']);
            $table->enum('bed_count', ['Single', 'Double', 'Triple', 'Quad']);
            $table->foreignId('view_id')->nullable()->constrained('room_views')->nullOnDelete();
            $table->decimal('base_price', 10, 2);
            $table->integer('max_occupancy')->default(2);
            $table->integer('floor_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
