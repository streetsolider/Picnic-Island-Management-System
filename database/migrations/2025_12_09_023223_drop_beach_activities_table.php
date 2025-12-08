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
        Schema::dropIfExists('beach_activities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('beach_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_service_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->boolean('requires_booking')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
