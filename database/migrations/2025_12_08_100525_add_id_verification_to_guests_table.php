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
        Schema::table('guests', function (Blueprint $table) {
            // ID Verification fields (simplified)
            $table->enum('id_type', ['national_id', 'passport'])
                  ->nullable()
                  ->after('phone');
            $table->string('id_number', 50)->nullable()->after('id_type');
            $table->string('nationality', 100)->default('Maldivian')->nullable()->after('id_number');
            $table->date('date_of_birth')->nullable()->after('nationality');
            $table->text('address')->nullable()->after('date_of_birth');

            // Index for faster lookups
            $table->index('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropIndex(['id_number']);
            $table->dropColumn([
                'id_type',
                'id_number',
                'nationality',
                'date_of_birth',
                'address',
            ]);
        });
    }
};
