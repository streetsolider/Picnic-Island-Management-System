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
            $table->integer('capacity_limit')->default(0)->after('description');
            $table->time('opening_time')->default('09:00:00')->after('capacity_limit');
            $table->time('closing_time')->default('18:00:00')->after('opening_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_zones', function (Blueprint $table) {
            $table->dropColumn(['capacity_limit', 'opening_time', 'closing_time']);
        });
    }
};
