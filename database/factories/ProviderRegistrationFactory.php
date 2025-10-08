<?php

namespace Database\Factories;

use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProviderRegistration>
 */
class ProviderRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'space_id' => Space::factory(),
            'submitted_by' => User::factory(),
            'company_name' => fake()->company(),
            'company_registration_number' => 'EU' . fake()->numberBetween(100000000, 999999999),
            'company_country' => fake()->country(),
            'company_address' => fake()->address(),
            'company_website' => fake()->url(),
            'contact_person_name' => fake()->name(),
            'contact_person_title' => fake()->jobTitle(),
            'contact_person_email' => fake()->safeEmail(),
            'contact_person_phone' => fake()->phoneNumber(),
            'ai_systems_description' => fake()->paragraphs(2, true),
            'ai_system_types' => fake()->randomElements(['general_purpose', 'high_risk', 'limited_risk', 'minimal_risk'], rand(1, 3)),
            'intended_use_cases' => fake()->paragraph(),
            'additional_notes' => fake()->optional()->paragraph(),
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the registration is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_notes' => null,
        ]);
    }

    /**
     * Indicate that the registration is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reviewed_by' => User::factory()->ecStaff(),
            'reviewed_at' => now(),
            'review_notes' => fake()->optional()->sentence(),
        ]);
    }

    /**
     * Indicate that the registration is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'reviewed_by' => User::factory()->ecStaff(),
            'reviewed_at' => now(),
            'review_notes' => fake()->sentence(),
        ]);
    }
}
