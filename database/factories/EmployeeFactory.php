<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nationality' => fake()->name(),
            'salary' => fake()->numberBetween(100,10000),
            'hour' => fake()->numberBetween(10,100),
            'passport_no' => '233576548987',
            'birthday' => fake()->date('Y_m_d'),
            'passport_expiry' => fake()->date('Y_m_d'),
            'card_expiry' => fake()->date('Y_m_d'),
            'join_date' => fake()->date('Y_m_d'),
        ];
    }
}
