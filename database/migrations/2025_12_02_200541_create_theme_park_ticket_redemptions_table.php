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
        Schema::create('theme_park_ticket_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('theme_park_activities')->onDelete('cascade');
            $table->unsignedInteger('tickets_redeemed');
            $table->enum('status', ['pending', 'validated', 'cancelled'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->string('redemption_reference')->unique(); // TPR-XXXXXXXX
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('activity_id');
            $table->index('status');
            $table->index('redemption_reference');
            $table->index('validated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_park_ticket_redemptions');
    }
};
