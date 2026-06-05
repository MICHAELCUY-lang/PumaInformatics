<?php

namespace Database\Factories;

use App\Models\VotingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VotingSession>
 */
class VotingSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => 'draft',
            'results_visibility' => 'hidden',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(3),
        ];
    }
}
