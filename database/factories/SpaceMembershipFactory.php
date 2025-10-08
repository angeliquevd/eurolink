<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpaceMembership>
 */
class SpaceMembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'space_id' => \App\Models\Space::factory(),
            'role_in_space' => fake()->randomElement(['owner', 'moderator', 'member']),
        ];
    }
}
