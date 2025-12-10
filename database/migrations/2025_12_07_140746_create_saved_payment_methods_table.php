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
        Schema::create('saved_payment_methods', function (Blueprint $table) {
            $table->id();

            // Guest relationship
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();

            // Card details (masked/tokenized)
            $table->string('card_token', 100)->unique(); // Fake token: TKN-XXXXXXXXXXXXXXXX
            $table->enum('bank', ['MIB', 'BML', 'CBM']);
            $table->enum('card_type', ['Visa', 'Mastercard']);
            $table->string('card_last_four', 4);
            $table->string('card_expiry', 7); // Format: MM/YYYY
            $table->string('card_holder_name', 100);

            // Usage tracking
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['guest_id', 'is_default']);
            $table->index('card_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_payment_methods');
    }
};
