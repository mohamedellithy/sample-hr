<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeAttendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeAttendance::factory()->count(20)->create();

    }
}
