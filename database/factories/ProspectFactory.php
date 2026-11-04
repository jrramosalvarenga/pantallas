<?php

namespace Database\Factories;

use App\Models\Municipality;
use App\Models\Prospect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prospect>
 */
class ProspectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_name' => fake()->company(),
            'category' => fake()->randomElement(['Comida', 'Salud', 'Finanzas', 'Comercio', 'Educación']),
            'municipality_id' => Municipality::inRandomOrder()->value('id'),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'contact_name' => fake()->name(),
            'status' => fake()->randomElement(array_keys(Prospect::STATUSES)),
            'priority' => fake()->randomElement(['A', 'B', 'C']),
        ];
    }
}
