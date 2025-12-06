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
        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Drop the old foreign key constraint that references users table
            $table->dropForeign(['redeemed_by_staff_id']);
        });

        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Add new foreign key referencing staff table instead of users table
            $table->foreign('redeemed_by_staff_id')
                  ->references('id')
                  ->on('staff')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Drop the staff foreign key
            $table->dropForeign(['redeemed_by_staff_id']);
        });

        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Restore original foreign key to users table
            $table->foreign('redeemed_by_staff_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
