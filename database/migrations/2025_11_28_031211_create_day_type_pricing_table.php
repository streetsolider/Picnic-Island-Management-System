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
        Schema::create('day_type_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('day_type_name'); // e.g., "Weekend", "Weekday", "Friday Night"
            $table->json('applicable_days'); // [5, 6] for Friday, Saturday (0=Sunday, 6=Saturday)
            $table->enum('modifier_type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('modifier_value', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_type_pricing');
    }
};
