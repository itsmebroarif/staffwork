<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Violation>
 */
class ViolationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory()->murid(),
            'reported_by' => User::factory()->guru(),
            'description' => $this->faker->sentence(),
            'room' => $this->faker->randomElement(['bk', 'guru']),
        ];
    }
}
