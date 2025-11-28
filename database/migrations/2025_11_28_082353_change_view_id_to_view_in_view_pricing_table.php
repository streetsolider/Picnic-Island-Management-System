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
        Schema::table('view_pricing', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['view_id']);

            // Drop the view_id column
            $table->dropColumn('view_id');

            // Add the new view column as string (Garden or Beach)
            $table->string('view')->after('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_pricing', function (Blueprint $table) {
            // Remove the view column
            $table->dropColumn('view');

            // Restore the view_id column
            $table->foreignId('view_id')->after('hotel_id')->constrained('room_views')->onDelete('cascade');
        });
    }
};
