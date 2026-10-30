<?php

namespace Database\Factories;

use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeachingSchedule>
 */
class TeachingScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => TeachingAssignment::factory(),
            'day' => $this->faker->randomElement(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']),
            'start_time' => '07:00',
            'end_time' => '09:00',
        ];
    }
}
