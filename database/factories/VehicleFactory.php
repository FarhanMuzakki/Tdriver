<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [

            'plate_number' => strtoupper(fake()->bothify('B #### ???')),

            'type' => fake()->randomElement([
                'SUV',
                'Pickup',
                'Truck',
                'Van'
            ]),

            'status' => fake()->randomElement([
                'available',
                'in_use',
                'maintenance'
            ]),

            'service_date' => fake()->dateTimeBetween('now', '+2 months'),
        ];
    }
}