<?php

namespace Database\Seeders;



use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use App\Models\EmployeeAttendance;
use Database\Seeders\EmployeeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
             AdminSeeder::class,
             EmployeeSeeder::class,
             ExpensesSeeder::class,
             EmployeeSaleSeeder::class,
             ClientsSeeder::class,
             ClientsSalesSeeder::class,
             EmployeeAdvancesSeeder::class,
             //EmployeeSalariesSeeder::class,
             EmployeeAttendancesSeeder::class,
        ]);

    }
}
