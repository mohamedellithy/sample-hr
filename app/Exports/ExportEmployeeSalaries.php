<?php

namespace App\Exports;

use DateTime;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeSale;
use Illuminate\Http\Request;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeSalarie;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployeeSalaries implements FromCollection ,WithMapping ,WithHeadings
{

    private $request,$employee,$startDate,$endDate;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
          // Calculate the first and end day of the given year and month and week
        $startDate = Carbon::create($this->request->year, $this->request->month, 1)->startOfMonth();
        $startDate->addWeeks($this->request->week - 1);
        $endDate =$startDate->copy()->addWeeks(1);
        $this->startDate = $startDate->toDateString();
        $this->endDate = $endDate->toDateString();


        $employeeCollection = new Collection();

        for ($currentDate = $this->startDate; $currentDate <=  $this->endDate; $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'))) {


            $employee = Employee::find($this->request->employee_id);
            if($employee){

                $Sales = EmployeeSale::where('employee_id',$this->request->employee_id)->where('sale_date', $currentDate)->sum('remained');

                $Advance = EmployeeAdvance::where('employee_id',$this->request->employee_id)->where('advance_date', $currentDate)->sum('amount');

                $Deduction = EmployeeSalarie::where('employee_id',$this->request->employee_id)->where('date', $currentDate)->sum('deduction');

                $Over_time = EmployeeSalarie::where('employee_id',$this->request->employee_id)->where('date', $currentDate)->sum('over_time');

                 if($Sales > 0 || $Advance >0 || $Deduction > 0 || $Over_time >0 )
                $employeeCollection->push([
                    'name' => $employee->name,
                    'Date' => $currentDate,
                    'Sales' =>  $Sales,
                    'Advance' => $Advance,
                    'Deduction' => $Deduction,
                    'Over_time' => $Over_time,
            ]);
            }

        }


        $employeeSalarie = EmployeeSalarie::with('employee')
        ->where('employee_id',$this->request->employee_id)
        ->whereBetween('date',[$this->startDate,$this->endDate])->get();


        $sumSales = EmployeeSale::where('employee_id',$this->request->employee_id)
        ->whereBetween('sale_date',[$this->startDate,$this->endDate])->sum('remained');

        $sumAdvance = EmployeeAdvance::where('employee_id',$this->request->employee_id)
        ->whereBetween('advance_date',[$this->startDate,$this->endDate])->sum('amount');


        $employeeCollection->push([
            'Total',
            '',
            $sumAdvance,
            $sumSales,
            $employeeSalarie->sum('deduction'),
            $employeeSalarie->sum('over_time'),
            $sumAdvance + $sumSales + $employeeSalarie->sum('deduction') - $employeeSalarie->sum('over_time'),

        ]);

       // dd($employeeCollection);
       return $employeeCollection;


    }

    public function map($row): array
    {

        if(isset($row['name'])){

            return [
                $row['name'],
                $row['Date'],
                $row['Advance'],
                $row['Sales'],
                $row['Deduction'],
                $row['Over_time'],
                $row['Advance'] +  $row['Sales'] + $row['Deduction'] - $row['Over_time']

            ];
        }else{
            return $row;
        }

    }


    public function headings(): array
    {
        return ["الاسم","التاريخ","السلف","آجل المبيعات","الخصومات","الاضافي","المجموع"];
    }
}
