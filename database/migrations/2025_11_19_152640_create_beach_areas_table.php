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
        Schema::create('beach_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location'); // e.g., North Beach, South Beach
            $table->text('description')->nullable();
            $table->integer('capacity_limit')->default(0);
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->foreignId('assigned_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beach_areas');
    }
};
