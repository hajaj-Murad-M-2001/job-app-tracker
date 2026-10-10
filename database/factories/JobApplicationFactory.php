<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'position' => fake()->jobTitle(),
            'status' => fake()->randomElement(ApplicationStatus::cases()),
            'job_url' => fake()->url(),
            'salary' => fake()->numberBetween(500, 5000),
            'notes' => fake()->sentence(),
            'applied_at' => fake()->date(),
        ];
    }
}