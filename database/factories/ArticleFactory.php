<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'content' => fake()->paragraphs(3, true),
            'excerpt' => fake()->paragraph(),
            'status' => 'published',
            'published_at' => now(),
            'is_featured' => fake()->boolean(20),
            'author_id' => \App\Models\User::factory(),
            'reading_time_minutes' => fake()->numberBetween(1, 10),
        ];
    }
}
