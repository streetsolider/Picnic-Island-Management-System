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
        Schema::create('duration_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('discount_name'); // e.g., "3-Night Stay", "Weekly Stay"
            $table->integer('minimum_nights');
            $table->integer('maximum_nights')->nullable(); // null = no maximum
            $table->enum('discount_type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
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
        Schema::dropIfExists('duration_discounts');
    }
};
