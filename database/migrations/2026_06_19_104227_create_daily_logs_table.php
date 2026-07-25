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
        Schema::create('daily_logs', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('vehicle_id');
            $table->uuid('driver_id');

            $table->enum('type', [
                'start_trip',
                'end_trip',
                'maintenance'
            ]);

            $table->text('description')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();

            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};