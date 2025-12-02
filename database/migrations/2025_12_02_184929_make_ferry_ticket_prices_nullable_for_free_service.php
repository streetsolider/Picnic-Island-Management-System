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
        Schema::table('ferry_tickets', function (Blueprint $table) {
            // Make price fields default to 0 for free service
            $table->decimal('price_per_passenger', 10, 2)->default(0)->change();
            $table->decimal('total_price', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_tickets', function (Blueprint $table) {
            // Revert to non-nullable
            $table->decimal('price_per_passenger', 10, 2)->default(null)->change();
            $table->decimal('total_price', 10, 2)->default(null)->change();
        });
    }
};
