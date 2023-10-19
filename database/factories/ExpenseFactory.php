<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'service' => fake()->randomElement(["بار","شيشه","صيانه","مطبخ","owner"]),
            'amount' => fake()->numberBetween(10,100),
            'expense_date' => fake()->date('Y_m_d'),
            'attachment' => 'http://127.0.0.1:8000/uploads/expense/default.jpg',
        ];
    }
}
