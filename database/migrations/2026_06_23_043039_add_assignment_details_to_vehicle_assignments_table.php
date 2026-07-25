<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->string('destination')->nullable();
            $table->string('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('planned_return_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'destination',
                'purpose',
                'notes',
                'planned_return_at',
            ]);
        });
    }
};