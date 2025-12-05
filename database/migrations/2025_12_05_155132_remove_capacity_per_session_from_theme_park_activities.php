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
            $table->dropColumn('capacity_per_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_activities', function (Blueprint $table) {
            $table->unsignedInteger('capacity_per_session')
                  ->default(50)
                  ->after('ticket_cost');
        });
    }
};
