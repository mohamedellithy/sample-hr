<?php

namespace Database\Seeders;

use App\Models\EmployeeSale;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeSale::factory()->count(20)->create();
    }
}
