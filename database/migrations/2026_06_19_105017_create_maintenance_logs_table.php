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
        Schema::create('maintenance_logs', function (Blueprint $table) {

            $table->uuid('id')->primary();

            // Kendaraan yang diservis
            $table->uuid('vehicle_id');

            // Jenis servis
            $table->enum('service_type', [
                'oil_change',
                'tire_change',
                'engine',
                'brake',
                'general_service',
                'other'
            ]);

            // Tanggal servis
            $table->date('service_date');

            // Biaya servis
            $table->decimal('cost', 12, 2)->default(0);

            // Nama bengkel
            $table->string('workshop')->nullable();

            // Kilometer saat servis
            $table->integer('odometer')->nullable();

            // Catatan
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};