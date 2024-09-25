<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RAcingLog>
 */
class RacingLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lap' => $this->faker->numberBetween(1, 4),
            'completed_in' => $this->faker->time,
            'avarage_vel' => $this->faker->randomFloat(2, 10, 50),
        ];
    }
}
