<?php

namespace Database\Factories;

use App\Models\AspirationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AspirationCategory>
 */
class AspirationCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Aspirations',
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
