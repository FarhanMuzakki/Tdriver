<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Data assignment lama dihapus
        |--------------------------------------------------------------------------
        | Dibutuhkan karena struktur vehicle_id sempat berubah dan migration gagal.
        */

        DB::statement('DELETE FROM vehicle_assignments');

        /*
        |--------------------------------------------------------------------------
        | Bersihkan constraint dan kolom lama
        |--------------------------------------------------------------------------
        */

        DB::statement('
            ALTER TABLE vehicle_assignments
            DROP CONSTRAINT IF EXISTS vehicle_assignments_vehicle_id_foreign
        ');

        DB::statement('
            ALTER TABLE vehicle_assignments
            DROP COLUMN IF EXISTS vehicle_id
        ');

        /*
        |--------------------------------------------------------------------------
        | vehicles.id adalah UUID, jadi vehicle_id juga harus UUID
        |--------------------------------------------------------------------------
        */

        DB::statement('
            ALTER TABLE vehicle_assignments
            ADD COLUMN vehicle_id UUID NOT NULL
        ');

        DB::statement('
            ALTER TABLE vehicle_assignments
            ADD CONSTRAINT vehicle_assignments_vehicle_id_foreign
            FOREIGN KEY (vehicle_id)
            REFERENCES vehicles(id)
            ON DELETE CASCADE
        ');

        DB::statement('
            CREATE INDEX IF NOT EXISTS
            vehicle_assignments_vehicle_id_index
            ON vehicle_assignments(vehicle_id)
        ');
    }

    public function down(): void
    {
        DB::statement('DELETE FROM vehicle_assignments');

        DB::statement('
            DROP INDEX IF EXISTS
            vehicle_assignments_vehicle_id_index
        ');

        DB::statement('
            ALTER TABLE vehicle_assignments
            DROP CONSTRAINT IF EXISTS vehicle_assignments_vehicle_id_foreign
        ');

        DB::statement('
            ALTER TABLE vehicle_assignments
            DROP COLUMN IF EXISTS vehicle_id
        ');

        DB::statement('
            ALTER TABLE vehicle_assignments
            ADD COLUMN vehicle_id UUID NULL
        ');
    }
};