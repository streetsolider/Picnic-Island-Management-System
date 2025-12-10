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
        Schema::table('rooms', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['view_id']);

            // Drop the view_id column
            $table->dropColumn('view_id');

            // Add the new view column as string (Garden or Beach)
            $table->string('view')->nullable()->after('bed_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Remove the view column
            $table->dropColumn('view');

            // Restore the view_id column
            $table->foreignId('view_id')->nullable()->constrained('room_views')->onDelete('set null');
        });
    }
};
