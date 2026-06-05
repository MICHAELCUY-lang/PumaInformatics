<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\ProjectCategory::factory(),
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'excerpt' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'status' => 'published',
            'is_featured' => fake()->boolean(20),
            'start_date' => fake()->date(),
            'completion_date' => fake()->optional()->date(),
            'github_url' => 'https://github.com/president-university/' . fake()->word(),
            'demo_url' => fake()->url(),
        ];
    }
}
