<?php

namespace App\Exports;

use DateTime;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
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

        $employeeSalarie = EmployeeSalarie::with('employee')->where('employee_id',$this->request->employee_id)->whereBetween('date',[$this->startDate,$this->endDate])->get();

        $employeeSalarie->push([
            'Total',
            '',
            $employeeSalarie->sum('advances'),
            $employeeSalarie->sum('sales'),
            $employeeSalarie->sum('deduction'),
            $employeeSalarie->sum('over_time'),
            $employeeSalarie->sum('advances')+$employeeSalarie->sum('sales')+$employeeSalarie->sum('deduction')-$employeeSalarie->sum('over_time'),

        ]);


       return $employeeSalarie;


    }

    public function map($row): array
    {
        if(isset($row['employee'])){
            return [
                $row['employee']['name'],
                $row['date'],
                $row['advances'],
                $row['sales'],
                $row['deduction'],
                $row['over_time'],
                $row['advances'] + $row['sales'] + $row['deduction'] - $row['over_time']

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
