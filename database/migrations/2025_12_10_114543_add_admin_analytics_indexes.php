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
        // Hotel bookings - Critical for revenue queries
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->index(['payment_status', 'status', 'created_at'], 'idx_bookings_revenue');
            $table->index('checked_in_at', 'idx_bookings_checkin');
        });

        // Ferry tickets - For operational stats (not revenue)
        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->index(['status', 'travel_date'], 'idx_ferry_ops');
        });

        // Beach service bookings - For revenue queries
        Schema::table('beach_service_bookings', function (Blueprint $table) {
            $table->index(['payment_status', 'status', 'booking_date'], 'idx_beach_revenue');
        });

        // Theme park wallet transactions - For revenue tracking
        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->index(['transaction_type', 'created_at'], 'idx_wallet_revenue');
        });

        // Payments - For unified payment analytics
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_payments_analytics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_revenue');
            $table->dropIndex('idx_bookings_checkin');
        });

        Schema::table('ferry_tickets', function (Blueprint $table) {
            $table->dropIndex('idx_ferry_ops');
        });

        Schema::table('beach_service_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_beach_revenue');
        });

        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_wallet_revenue');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_analytics');
        });
    }
};
