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
        Schema::create('seasonal_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('season_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('modifier_type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('modifier_value', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Higher priority overrides lower
            $table->timestamps();

            $table->index(['hotel_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasonal_pricing');
    }
};
