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
   Schema::create('vehicles', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->string('plate_number')->unique();
    $table->string('type');

    $table->enum('status', ['available', 'in_use', 'maintenance'])
        ->default('available');

    $table->date('service_date')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
