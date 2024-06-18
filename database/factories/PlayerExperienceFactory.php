<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerExperience>
 */
class PlayerExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'placement' => $this->faker->numberBetween(1, 16),
            'event' => $this->faker->sentence(),
            'team' => $this->faker->company(),
            'job' => $this->faker->jobTitle(),
            'date' => $this->faker->date(),
        ];
    }
}
