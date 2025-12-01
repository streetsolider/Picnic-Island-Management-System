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
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['guest_id']);

            // Add new foreign key pointing to guests table
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
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop the guests foreign key
            $table->dropForeign(['guest_id']);

            // Restore the old foreign key pointing to users table
            $table->foreign('guest_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
