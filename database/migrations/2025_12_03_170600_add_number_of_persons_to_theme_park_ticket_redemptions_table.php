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
        Schema::table('theme_park_ticket_redemptions', function (Blueprint $table) {
            $table->unsignedInteger('number_of_persons')->default(1)->after('tickets_redeemed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_ticket_redemptions', function (Blueprint $table) {
            $table->dropColumn('number_of_persons');
        });
    }
};
