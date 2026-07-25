<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable();
            $table->string('license_number', 50)->nullable();
            $table->date('license_expiry')->nullable();
            $table->text('address')->nullable();
            $table->string('driver_status')->default('active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'license_number',
                'license_expiry',
                'address',
                'driver_status',
            ]);
        });
    }
};