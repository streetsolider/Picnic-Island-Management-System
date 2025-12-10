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
        Schema::table('theme_park_zones', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')
                  ->nullable()
                  ->after('is_active')
                  ->constrained('staff')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_zones', function (Blueprint $table) {
            $table->dropForeign(['assigned_staff_id']);
            $table->dropColumn('assigned_staff_id');
        });
    }
};
