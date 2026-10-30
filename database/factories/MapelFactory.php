<?php

namespace Database\Factories;

use App\Models\Mapel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mapel>
 */
class MapelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'MP'.$this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->unique()->word().' '.$this->faker->numberBetween(1, 99),
        ];
    }
}
