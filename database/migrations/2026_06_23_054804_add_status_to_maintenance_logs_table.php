<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table
                ->string('status')
                ->default('scheduled')
                ->after('notes');

            $table
                ->timestamp('completed_at')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'completed_at',
            ]);
        });
    }
};
