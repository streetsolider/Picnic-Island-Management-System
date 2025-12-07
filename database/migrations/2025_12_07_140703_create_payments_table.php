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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship to booking types (nullable until booking is created)
            $table->nullableMorphs('payable'); // payable_id, payable_type

            // Guest relationship
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();

            // Transaction details
            $table->string('transaction_id', 50)->unique(); // Format: TXN-YYYYMMDD-XXXXXXXX
            $table->string('payment_reference', 50)->unique(); // Format: PAY-XXXXXXXX

            // Amount
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MVR');

            // Bank & Card details
            $table->enum('bank', ['MIB', 'BML', 'CBM']);
            $table->enum('card_type', ['Visa', 'Mastercard']);
            $table->string('card_last_four', 4);
            $table->string('card_token', 100)->nullable(); // Fake tokenized card data

            // Payment status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('failure_reason')->nullable();

            // Timestamps
            $table->timestamp('initiated_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // Metadata (JSON for additional details)
            $table->json('metadata')->nullable(); // Store simulation details, IP, user agent, etc.

            $table->timestamps();

            // Indexes
            $table->index('transaction_id');
            $table->index('payment_reference');
            $table->index(['guest_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
