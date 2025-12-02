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
            // Remove name column (will be auto-generated)
            $table->dropColumn('name');
            // Remove base_price column (ferry service is free)
            $table->dropColumn('base_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            // Restore columns
            $table->string('name')->after('id');
            $table->decimal('base_price', 10, 2)->after('duration_minutes');
        });
    }
};
