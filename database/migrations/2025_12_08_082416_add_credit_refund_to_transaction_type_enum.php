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
        // Add 'credit_refund' to the transaction_type enum
        DB::statement("ALTER TABLE theme_park_wallet_transactions
            MODIFY COLUMN transaction_type ENUM('top_up', 'ticket_purchase', 'activity_ticket_purchase', 'credit_refund') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'credit_refund' from the enum
        DB::statement("ALTER TABLE theme_park_wallet_transactions
            MODIFY COLUMN transaction_type ENUM('top_up', 'ticket_purchase', 'activity_ticket_purchase') NOT NULL");
    }
};
