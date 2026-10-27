<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->guru(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['tugas', 'ujian']),
            'duration_minutes' => fake()->numberBetween(30, 120),
            'available_at' => now(),
        ];
    }

    public function tugas(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'tugas']);
    }

    public function ujian(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'ujian']);
    }
}
