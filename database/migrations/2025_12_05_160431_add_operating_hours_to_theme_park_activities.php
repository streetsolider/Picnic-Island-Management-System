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
            $table->time('operating_hours_start')
                  ->nullable()
                  ->after('duration_minutes')
                  ->comment('Activity start time; NULL = follows zone hours');

            $table->time('operating_hours_end')
                  ->nullable()
                  ->after('operating_hours_start')
                  ->comment('Activity end time; NULL = follows zone hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_activities', function (Blueprint $table) {
            $table->dropColumn(['operating_hours_start', 'operating_hours_end']);
        });
    }
};
