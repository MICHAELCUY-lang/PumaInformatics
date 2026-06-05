<?php

namespace Database\Factories;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vote>
 */
class VoteFactory extends Factory
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
            'candidate_id' => \App\Models\Candidate::factory(),
            'user_id' => \App\Models\User::factory(),
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'fingerprint_hash' => hash('sha256', fake()->userAgent()),
        ];
    }
}
