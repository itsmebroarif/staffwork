<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaliMurid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaliMurid>
 */
class WaliMuridFactory extends Factory
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
            'name' => $this->faker->name(),
            'hubungan' => $this->faker->randomElement(['Ayah', 'Ibu', 'Wali']),
            'phone' => $this->faker->numerify('08##########'),
            'email' => $this->faker->safeEmail(),
            'alamat' => $this->faker->address(),
        ];
    }
}
