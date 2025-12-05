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
        // Add activity_ticket_id column for linking transactions to activity tickets
        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->foreignId('activity_ticket_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('theme_park_activity_tickets')
                  ->onDelete('set null')
                  ->comment('Links to activity ticket when transaction_type = activity_ticket_purchase');
        });

        // Modify enum to add 'activity_ticket_purchase' type
        DB::statement("ALTER TABLE theme_park_wallet_transactions
            MODIFY COLUMN transaction_type ENUM('top_up', 'ticket_purchase', 'activity_ticket_purchase') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the new transaction type from enum
        DB::statement("ALTER TABLE theme_park_wallet_transactions
            MODIFY COLUMN transaction_type ENUM('top_up', 'ticket_purchase') NOT NULL");

        // Drop the activity_ticket_id column
        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['activity_ticket_id']);
            $table->dropColumn('activity_ticket_id');
        });
    }
};
