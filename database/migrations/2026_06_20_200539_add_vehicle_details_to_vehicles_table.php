<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('color')->nullable();

            $table->enum('fuel_type', [
                'gasoline',
                'diesel',
                'electric',
                'hybrid',
            ])->nullable();

            $table->enum('transmission', [
                'manual',
                'automatic',
            ])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'model',
                'year',
                'color',
                'fuel_type',
                'transmission',
            ]);
        });
    }
};