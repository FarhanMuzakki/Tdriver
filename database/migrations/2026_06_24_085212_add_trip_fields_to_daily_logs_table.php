<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_logs', 'driver_id')) {
                $table->foreignUuid('driver_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('daily_logs', 'vehicle_id')) {
                $table->foreignUuid('vehicle_id')
                    ->nullable()
                    ->constrained('vehicles')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('daily_logs', 'log_date')) {
                $table->date('log_date')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'start_time')) {
                $table->time('start_time')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'end_time')) {
                $table->time('end_time')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'destination')) {
                $table->string('destination')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'purpose')) {
                $table->string('purpose')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'start_odometer')) {
                $table->unsignedBigInteger('start_odometer')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'end_odometer')) {
                $table->unsignedBigInteger('end_odometer')->nullable();
            }

            if (!Schema::hasColumn('daily_logs', 'fuel_cost')) {
                $table->decimal('fuel_cost', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('daily_logs', 'toll_cost')) {
                $table->decimal('toll_cost', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('daily_logs', 'parking_cost')) {
                $table->decimal('parking_cost', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('daily_logs', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        /*
         * Kosongkan dulu supaya tidak menghapus kolom lama
         * yang bukan dibuat oleh migration ini.
         */
    }
};