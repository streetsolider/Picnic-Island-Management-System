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
        // Drop the foreign key constraint first
        DB::statement('ALTER TABLE view_pricing DROP FOREIGN KEY view_pricing_view_id_foreign');

        // Drop the column (this will automatically drop the index)
        DB::statement('ALTER TABLE view_pricing DROP COLUMN view_id');

        // Add the new view column as string
        DB::statement('ALTER TABLE view_pricing ADD COLUMN view VARCHAR(255) NOT NULL AFTER hotel_id');

        // Add new unique constraint for hotel_id and view
        DB::statement('ALTER TABLE view_pricing ADD UNIQUE KEY view_pricing_hotel_id_view_unique (hotel_id, view)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_pricing', function (Blueprint $table) {
            // Drop the unique constraint for hotel_id and view
            $table->dropUnique(['hotel_id', 'view']);

            // Remove the view column
            $table->dropColumn('view');

            // Restore the view_id column
            $table->foreignId('view_id')->after('hotel_id')->constrained('room_views')->onDelete('cascade');

            // Restore the unique constraint for hotel_id and view_id
            $table->unique(['hotel_id', 'view_id']);
        });
    }
};
