<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('daily_log_id')
                ->constrained('daily_logs')
                ->cascadeOnDelete();

            $table->string('type', 30);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'daily_log_id',
                'type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_receipts');
    }
};