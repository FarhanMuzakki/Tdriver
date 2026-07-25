<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('image_path')
                ->nullable()
                ->after('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')
                ->nullable()
                ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};