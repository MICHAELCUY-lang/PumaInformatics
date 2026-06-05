<?php

namespace Database\Factories;

use App\Models\Navigation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Navigation>
 */
class NavigationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'url' => '/' . fake()->word(),
            'is_external' => false,
            'is_active' => true,
            'order' => fake()->numberBetween(0, 100),
            'visibility_roles' => null,
        ];
    }
}
