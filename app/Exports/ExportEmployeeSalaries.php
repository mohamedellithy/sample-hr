<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployeeSalaries implements FromCollection ,WithMapping ,WithHeadings
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employeeSalaries = EmployeeSalarie::query();
        $employeeSalaries= $employeeSalaries->with('employee');

        if ($this->request->has('employee_filter') and $this->request->get('employee_filter') != "") {

            $employeeSalaries->where('employee_id',$this->request->get('employee_filter'));
        }


        $employeeSalaries->when($this->request->filter == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        return $employeeSalaries->get();

    }

    public function map($employeeSalaries): array
    {
        return [
            $employeeSalaries->employee->name,
            $employeeSalaries->days,
            $employeeSalaries->employee->salary,
            $employeeSalaries->employee->hour,
            $employeeSalaries->days > 30 ?  formate_price(($employeeSalaries->days - 30 ) * 24 * $employeeSalaries->employee->hour): null,
            $employeeSalaries->created_at,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","الايام","المرتب","الساعه","اضافي","التاريخ"];
    }
}
