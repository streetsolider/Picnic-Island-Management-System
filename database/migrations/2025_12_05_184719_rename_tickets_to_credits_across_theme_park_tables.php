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
        // 1. theme_park_activities: ticket_cost → credit_cost
        Schema::table('theme_park_activities', function (Blueprint $table) {
            $table->renameColumn('ticket_cost', 'credit_cost');
        });

        // 2. theme_park_activity_tickets: tickets_used → credits_spent, number_of_persons → quantity
        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            $table->renameColumn('tickets_used', 'credits_spent');
            $table->renameColumn('number_of_persons', 'quantity');
        });

        // 3. theme_park_wallets: ticket_balance → credit_balance, and other ticket fields
        Schema::table('theme_park_wallets', function (Blueprint $table) {
            $table->renameColumn('ticket_balance', 'credit_balance');
            $table->renameColumn('total_tickets_purchased', 'total_credits_purchased');
            $table->renameColumn('total_tickets_redeemed', 'total_credits_redeemed');
        });

        // 4. theme_park_wallet_transactions: tickets_amount → credits_amount, balance fields
        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->renameColumn('tickets_amount', 'credits_amount');
            $table->renameColumn('balance_before_tickets', 'balance_before_credits');
            $table->renameColumn('balance_after_tickets', 'balance_after_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse all renames
        Schema::table('theme_park_activities', function (Blueprint $table) {
            $table->renameColumn('credit_cost', 'ticket_cost');
        });

        Schema::table('theme_park_activity_tickets', function (Blueprint $table) {
            $table->renameColumn('credits_spent', 'tickets_used');
            $table->renameColumn('quantity', 'number_of_persons');
        });

        Schema::table('theme_park_wallets', function (Blueprint $table) {
            $table->renameColumn('credit_balance', 'ticket_balance');
            $table->renameColumn('total_credits_purchased', 'total_tickets_purchased');
            $table->renameColumn('total_credits_redeemed', 'total_tickets_redeemed');
        });

        Schema::table('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->renameColumn('credits_amount', 'tickets_amount');
            $table->renameColumn('balance_before_credits', 'balance_before_tickets');
            $table->renameColumn('balance_after_credits', 'balance_after_tickets');
        });
    }
};
