<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voting_session_id' => \App\Models\VotingSession::factory(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'vision' => fake()->paragraph(),
            'mission' => fake()->paragraph(),
            'biography' => fake()->paragraph(),
            'order' => fake()->numberBetween(0, 10),
            'is_featured' => fake()->boolean(),
        ];
    }
}
