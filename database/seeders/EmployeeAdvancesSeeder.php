<?php

namespace Database\Seeders;

use App\Models\EmployeeAdvance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeAdvancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeAdvance::factory()->count(20)->create();

    }
}
