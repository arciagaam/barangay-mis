<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->firstNameFemale(),
            'last_name' => fake()->lastName(),
            'nickname' => fake()->firstNameMale(),
            'sex' => fake()->numberBetween(1,2),
            'birth_date' => fake()->date(),
            'age' => fake()->numberBetween(18,60),
            'place_of_birth' => fake()->city(),
            'civil_status_id' => fake()->numberBetween(1,5),
            'occupation_id' => fake()->numberBetween(1,2),
            'religion_id' => fake()->numberBetween(1,2),
            'household_id' => fake()->numberBetween(1, 10),
            'phone_number' => fake()->phoneNumber(),
            'telephone_number' => fake()->tollFreePhoneNumber(),
            'voter_status' => fake()->numberBetween(0,1),
            'disabled' => fake()->numberBetween(0,1),
        ];
    }
}
