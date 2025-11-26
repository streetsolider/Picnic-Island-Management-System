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
        Schema::table('ferry_vessels', function (Blueprint $table) {
            $table->string('registration_number')->unique()->after('name');
            $table->enum('vessel_type', ['Ferry', 'Speed Boat', 'Boat'])->default('Ferry')->after('registration_number');
            $table->foreignId('operator_id')->nullable()->after('capacity')->constrained('staff')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_vessels', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropColumn(['registration_number', 'vessel_type', 'operator_id']);
        });
    }
};
