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
        Schema::table('ferry_routes', function (Blueprint $table) {
            // Remove duration_minutes column (calculated from schedule times)
            $table->dropColumn('duration_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            // Restore duration_minutes column
            $table->integer('duration_minutes')->after('destination');
        });
    }
};
