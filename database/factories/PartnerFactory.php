<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\PartnerCategory::factory(),
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'website_url' => fake()->url(),
            'contact_email' => fake()->companyEmail(),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }
}
