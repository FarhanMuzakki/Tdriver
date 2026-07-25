<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->foreignUuid('maintenance_request_id')
                ->nullable()
                ->after('vehicle_id')
                ->constrained('maintenance_requests')
                ->nullOnDelete();

            $table->index('maintenance_request_id');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropForeign([
                'maintenance_request_id',
            ]);

            $table->dropIndex([
                'maintenance_request_id',
            ]);

            $table->dropColumn('maintenance_request_id');
        });
    }
};