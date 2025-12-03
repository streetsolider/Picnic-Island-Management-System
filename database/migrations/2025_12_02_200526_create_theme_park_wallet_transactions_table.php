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
        Schema::create('theme_park_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('transaction_type', ['top_up', 'ticket_purchase']);
            $table->decimal('amount_mvr', 10, 2)->nullable(); // For top-ups
            $table->unsignedInteger('tickets_amount')->nullable(); // For ticket purchases
            $table->decimal('balance_before_mvr', 10, 2)->default(0.00);
            $table->decimal('balance_after_mvr', 10, 2)->default(0.00);
            $table->unsignedInteger('balance_before_tickets')->default(0);
            $table->unsignedInteger('balance_after_tickets')->default(0);
            $table->string('transaction_reference')->unique(); // TPW-XXXXXXXX
            $table->string('payment_method')->nullable(); // For future payment integration
            $table->string('payment_reference')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('transaction_type');
            $table->index('transaction_reference');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_park_wallet_transactions');
    }
};
