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
        // Add payment_id to hotel_bookings table
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('payment_method')
                  ->constrained('payments')->nullOnDelete();
            $table->index('payment_id');
        });

        // Add payment_id to beach_service_bookings table
        Schema::table('beach_service_bookings', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('payment_status')
                  ->constrained('payments')->nullOnDelete();
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove payment_id from hotel_bookings table
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropIndex(['payment_id']);
            $table->dropColumn('payment_id');
        });

        // Remove payment_id from beach_service_bookings table
        Schema::table('beach_service_bookings', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropIndex(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
};
