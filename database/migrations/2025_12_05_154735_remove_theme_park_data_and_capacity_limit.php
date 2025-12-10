<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();

        // Truncate all theme park tables (order matters due to FK constraints)
        DB::table('theme_park_wallet_transactions')->truncate();
        DB::table('theme_park_ticket_redemptions')->truncate();
        DB::table('theme_park_activity_schedules')->truncate();
        DB::table('theme_park_activities')->truncate();
        DB::table('theme_park_wallets')->truncate();
        DB::table('theme_park_settings')->truncate();
        DB::table('theme_park_zones')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Remove capacity_limit column from theme_park_zones
        Schema::table('theme_park_zones', function (Blueprint $table) {
            $table->dropColumn('capacity_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add capacity_limit column back
        Schema::table('theme_park_zones', function (Blueprint $table) {
            $table->integer('capacity_limit')->default(0)->after('description');
        });
    }
};
