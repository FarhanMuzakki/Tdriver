<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\DailyLog;
use App\Models\VehicleAssignment;
use App\Models\MaintenanceLog;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Driver
        |--------------------------------------------------------------------------
        */

        User::factory(10)->create([
            'role' => 'driver'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vehicle
        |--------------------------------------------------------------------------
        */

        Vehicle::factory(20)->create();

        /*
        |--------------------------------------------------------------------------
        | Seeder lainnya menyusul
        |--------------------------------------------------------------------------
        */
    }
}