<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventCategory>
 */
class EventCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->word(),
            'slug' => fake()->unique()->slug(),
            'is_active' => true,
            'order' => fake()->numberBetween(0, 10),
            'color_accent' => fake()->hexColor(),
        ];
    }
}
