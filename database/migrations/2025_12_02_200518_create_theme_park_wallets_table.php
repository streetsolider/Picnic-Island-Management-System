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
        Schema::create('theme_park_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->decimal('balance_mvr', 10, 2)->default(0.00);
            $table->unsignedInteger('ticket_balance')->default(0);
            $table->decimal('total_topped_up_mvr', 10, 2)->default(0.00);
            $table->unsignedInteger('total_tickets_purchased')->default(0);
            $table->unsignedInteger('total_tickets_redeemed')->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_park_wallets');
    }
};
