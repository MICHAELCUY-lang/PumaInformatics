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
            'status' => VotingSession::STATUS_DRAFT,
            'results_visibility' => VotingSession::VISIBILITY_PRIVATE,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(3),
        ];
    }

    /**
     * A session that is live and accepting ballots right now.
     */
    public function open(): static
    {
        return $this->state(fn () => [
            'status' => VotingSession::STATUS_ACTIVE,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
    }

    /**
     * A session whose voting window has closed.
     */
    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => VotingSession::STATUS_COMPLETED,
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDay(),
        ]);
    }
}
