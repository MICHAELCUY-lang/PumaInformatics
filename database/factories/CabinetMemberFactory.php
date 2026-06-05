<?php

namespace Database\Factories;

use App\Models\CabinetMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CabinetMember>
 */
class CabinetMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => \App\Models\CabinetDepartment::factory(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'role_title' => fake()->jobTitle(),
            'role_hierarchy_level' => fake()->numberBetween(1, 100),
            'term_year' => '2026-2027',
            'is_active' => true,
            'biography' => fake()->paragraph(),
        ];
    }
}
