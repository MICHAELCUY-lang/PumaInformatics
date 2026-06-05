<?php

namespace Database\Factories;

use App\Models\PartnerCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerCategory>
 */
class PartnerCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Partners',
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
