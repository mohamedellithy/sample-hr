<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeAttendance>
 */
class EmployeeAttendanceFactory extends Factory
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
            'attendance_date'=>fake()->date('Y_m_d'),
            'clock_in'=>fake()->time(),
            'clock_out'=>fake()->time(),
        ];
    }
}
