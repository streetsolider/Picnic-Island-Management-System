<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing ferry tickets (user confirmed - invalid data)
        DB::table('ferry_tickets')->truncate();

        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->enum('direction', ['to_island', 'from_island'])->after('ferry_route_id');
            $table->index(['hotel_booking_id', 'direction']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->dropIndex(['hotel_booking_id', 'direction']);
            $table->dropColumn('direction');
        });
    }
};
