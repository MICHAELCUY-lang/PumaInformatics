<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+2 weeks');
        $endDate = clone $startDate;
        $endDate->modify('+2 hours');

        return [
            'category_id' => \App\Models\EventCategory::factory(),
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraphs(3, true),
            'excerpt' => fake()->paragraph(),
            'status' => 'published',
            'is_featured' => fake()->boolean(20),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'timezone' => 'Asia/Jakarta',
            'location_name' => fake()->company(),
            'location_address' => fake()->address(),
            'internal_rsvp_enabled' => fake()->boolean(),
        ];
    }
}
