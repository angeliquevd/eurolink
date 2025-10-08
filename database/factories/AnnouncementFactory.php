<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'space_id' => \App\Models\Space::factory(),
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(3, true),
            'created_by' => \App\Models\User::factory(),
            'published_at' => fake()->boolean(80) ? now() : null,
        ];
    }
}
