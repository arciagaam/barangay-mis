<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purok' => fake()->numberBetween(1, 999),
            'house_number' => fake()->numberBetween(1, 999),
            'block' => fake()->numberBetween(1, 999),
            'lot' => fake()->numberBetween(1, 999),
            'others' => fake()->streetName(),
            'subdivision' => fake()->state()
        ];
    }
}
