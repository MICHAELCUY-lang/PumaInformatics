<?php

namespace Database\Factories;

use App\Models\CabinetDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CabinetDepartment>
 */
class CabinetDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Department',
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
