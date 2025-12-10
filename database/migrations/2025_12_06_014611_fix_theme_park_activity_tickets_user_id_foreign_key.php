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
            // Drop the old foreign key constraint
            $table->dropForeign(['user_id']);

            // Rename user_id to guest_id for clarity (optional but recommended)
            $table->renameColumn('user_id', 'guest_id');
        });

        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Add new foreign key referencing guests table
            $table->foreign('guest_id')
                  ->references('id')
                  ->on('guests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Drop the guests foreign key
            $table->dropForeign(['guest_id']);

            // Rename back to user_id
            $table->renameColumn('guest_id', 'user_id');
        });

        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            // Restore original foreign key to users table
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
