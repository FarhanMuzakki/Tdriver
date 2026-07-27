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
       Schema::create('vehicle_assignments', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('vehicle_id');
    $table->uuid('driver_id');

    $table->timestamp('assigned_at')->nullable();
    $table->timestamp('returned_at')->nullable();

    $table->enum('status', ['active', 'finished'])->default('active');

    $table->timestamps();

    // FOREIGN KEYS
    $table->foreign('vehicle_id')
        ->references('id')->on('vehicles')
        ->cascadeOnDelete();

    $table->foreign('driver_id')
        ->references('id')->on('users')
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_assignments');
    }
};
