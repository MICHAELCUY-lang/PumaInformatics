<?php

namespace Database\Factories;

use App\Models\Aspiration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aspiration>
 */
class AspirationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\AspirationCategory::factory(),
            'subject' => fake()->sentence(),
            'payload' => fake()->paragraph(),
            'status' => 'pending',
            'visibility' => 'private',
            'is_anonymous' => fake()->boolean(),
        ];
    }
}
