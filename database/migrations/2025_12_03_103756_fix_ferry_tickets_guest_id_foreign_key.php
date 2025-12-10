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
        // Drop the foreign key constraint pointing to users table
        DB::statement('ALTER TABLE ferry_tickets DROP FOREIGN KEY ferry_tickets_guest_id_foreign');

        // Add new foreign key constraint pointing to guests table
        DB::statement('ALTER TABLE ferry_tickets ADD CONSTRAINT ferry_tickets_guest_id_foreign FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to users table
        DB::statement('ALTER TABLE ferry_tickets DROP FOREIGN KEY ferry_tickets_guest_id_foreign');
        DB::statement('ALTER TABLE ferry_tickets ADD CONSTRAINT ferry_tickets_guest_id_foreign FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
