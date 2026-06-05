<?php

namespace Database\Factories;

use App\Models\EventTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventTag>
 */
class EventTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
