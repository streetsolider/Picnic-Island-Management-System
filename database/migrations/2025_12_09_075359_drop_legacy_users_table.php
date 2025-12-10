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
        // Drop legacy users table (deprecated, replaced by guests and staff tables)
        Schema::dropIfExists('users');

        // Drop password_reset_tokens table (only used with users table)
        Schema::dropIfExists('password_reset_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible - this is a cleanup migration
    }
};
