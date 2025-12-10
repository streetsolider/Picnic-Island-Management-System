<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Check-in tracking
            $table->timestamp('checked_in_at')->nullable()->after('special_requests');
            $table->foreignId('checked_in_by')->nullable()->constrained('staff')->nullOnDelete()->after('checked_in_at');
            $table->text('check_in_notes')->nullable()->after('checked_in_by');

            // Check-out tracking
            $table->timestamp('checked_out_at')->nullable()->after('check_in_notes');
            $table->foreignId('checked_out_by')->nullable()->constrained('staff')->nullOnDelete()->after('checked_out_at');
            $table->text('check_out_notes')->nullable()->after('checked_out_by');

            // Additional fields
            $table->decimal('additional_charges', 10, 2)->default(0)->after('check_out_notes');
            $table->enum('room_condition', ['excellent', 'good', 'fair', 'poor', 'damaged'])->nullable()->after('additional_charges');
        });

        // Update the status enum to include new statuses
        DB::statement("ALTER TABLE hotel_bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'completed', 'cancelled', 'no-show') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['checked_in_by']);
            $table->dropForeign(['checked_out_by']);

            $table->dropColumn([
                'checked_in_at',
                'checked_in_by',
                'check_in_notes',
                'checked_out_at',
                'checked_out_by',
                'check_out_notes',
                'additional_charges',
                'room_condition',
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE hotel_bookings MODIFY COLUMN status ENUM('confirmed', 'cancelled', 'completed', 'no-show') DEFAULT 'confirmed'");
    }
};
