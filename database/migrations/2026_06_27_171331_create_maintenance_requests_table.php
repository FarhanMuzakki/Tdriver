<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('driver_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignUuid('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('issue_type', 100);

            $table->text('description');

            $table->string('priority', 20)
                ->default('medium');

            $table->string('status', 20)
                ->default('pending');

            $table->timestamp('requested_at')
                ->nullable();

            $table->text('admin_notes')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'driver_id',
                'status',
            ]);

            $table->index([
                'vehicle_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};