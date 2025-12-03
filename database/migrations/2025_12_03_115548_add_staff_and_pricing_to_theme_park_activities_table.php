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
        Schema::table('theme_park_activities', function (Blueprint $table) {
            // Staff assignment - manager assigns staff to activities
            $table->foreignId('assigned_staff_id')->nullable()->after('theme_park_zone_id')->constrained('staff')->nullOnDelete();

            // MVR pricing - manager sets ticket price in Maldivian Rufiyaa
            $table->decimal('ticket_price_mvr', 10, 2)->default(0)->after('ticket_cost');

            $table->index('assigned_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_activities', function (Blueprint $table) {
            $table->dropForeign(['assigned_staff_id']);
            $table->dropIndex(['assigned_staff_id']);
            $table->dropColumn(['assigned_staff_id', 'ticket_price_mvr']);
        });
    }
};
