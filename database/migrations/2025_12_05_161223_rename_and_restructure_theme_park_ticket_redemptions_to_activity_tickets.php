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
        // Drop old table (it's empty anyway)
        Schema::dropIfExists('theme_park_ticket_redemptions');

        // Create new table with improved structure
        Schema::create('theme_park_activity_tickets', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('activity_id')
                  ->constrained('theme_park_activities')
                  ->onDelete('cascade');

            $table->foreignId('show_schedule_id')
                  ->nullable()
                  ->constrained('theme_park_show_schedules')
                  ->onDelete('cascade')
                  ->comment('NULL for continuous rides, set for scheduled shows');

            // Ticket details
            $table->unsignedInteger('tickets_used')
                  ->comment('How many tickets were spent from wallet');

            $table->unsignedInteger('number_of_persons')
                  ->default(1)
                  ->comment('How many people this ticket admits');

            $table->decimal('total_credits_paid', 10, 2)
                  ->comment('Audit trail of total cost');

            // QR code and status
            $table->string('ticket_reference')
                  ->unique()
                  ->comment('QR code identifier (e.g., TPT-ABC12345)');

            $table->enum('status', ['valid', 'redeemed', 'expired', 'cancelled'])
                  ->default('valid');

            // Timestamps
            $table->timestamp('purchase_datetime')
                  ->useCurrent()
                  ->comment('When ticket was purchased');

            $table->timestamp('valid_until')
                  ->nullable()
                  ->comment('Ticket expiry; NULL = valid indefinitely');

            $table->foreignId('redeemed_by_staff_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Staff who validated/redeemed the ticket');

            $table->timestamp('redeemed_at')->nullable();

            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('activity_id');
            $table->index('show_schedule_id');
            $table->index('status');
            $table->index('ticket_reference');
            $table->index('purchase_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new table
        Schema::dropIfExists('theme_park_activity_tickets');

        // Recreate old table
        Schema::create('theme_park_ticket_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('theme_park_activities')->onDelete('cascade');
            $table->unsignedInteger('tickets_redeemed');
            $table->unsignedInteger('number_of_persons')->default(1);
            $table->enum('status', ['pending', 'validated', 'cancelled'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->string('redemption_reference')->unique();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('activity_id');
            $table->index('status');
            $table->index('redemption_reference');
            $table->index('validated_by');
        });
    }
};
