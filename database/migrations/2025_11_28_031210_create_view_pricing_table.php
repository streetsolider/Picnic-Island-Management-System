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
        Schema::create('view_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('view_id')->constrained('room_views')->cascadeOnDelete();
            $table->enum('modifier_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('modifier_value', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Each view should have only one pricing per hotel
            $table->unique(['hotel_id', 'view_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_pricing');
    }
};
