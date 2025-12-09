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
        Schema::table('view_pricing', function (Blueprint $table) {
            // Drop the incorrect unique constraint (only hotel_id)
            $table->dropUnique('view_pricing_hotel_id_view_id_unique');

            // The correct constraint (hotel_id + view) already exists:
            // view_pricing_hotel_id_view_unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_pricing', function (Blueprint $table) {
            // Re-add the constraint if rolling back
            $table->unique('hotel_id', 'view_pricing_hotel_id_view_id_unique');
        });
    }
};
