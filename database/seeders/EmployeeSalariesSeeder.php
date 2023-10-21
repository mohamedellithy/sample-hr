<?php

namespace Database\Seeders;

use App\Models\EmployeeSalarie;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSalariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeSalarie::factory()->count(20)->create();

    }
}
