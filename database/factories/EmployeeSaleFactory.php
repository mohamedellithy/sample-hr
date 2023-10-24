<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeSale>
 */
class EmployeeSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'employee_id'=>Employee::all()->random()->id,
            'amount'=>fake()->numberBetween(50,100),
            'remained'=>fake()->numberBetween(0,50),
            'sale_date'=>fake()->date('Y_m_d'),
        ];
    }
}
