<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $correct = fake()->randomElement(['A', 'B', 'C', 'D']);

        return [
            'exam_id' => Exam::factory(),
            'question' => fake()->sentence(),
            'option_a' => fake()->word(),
            'option_b' => fake()->word(),
            'option_c' => fake()->word(),
            'option_d' => fake()->word(),
            'correct_answer' => $correct,
            'order' => 1,
        ];
    }
}
