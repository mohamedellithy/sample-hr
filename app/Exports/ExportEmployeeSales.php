<?php

namespace App\Exports;

use App\Models\EmployeeSale;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployeeSales implements FromCollection ,WithMapping ,WithHeadings
{

    private $employee_filter;
    private $from;
    private $to;
    private $filter;

    public function __construct($employee_filter,$from,$to,$filter)
    {
        $this->employee_filter = $employee_filter;
        $this->from = $from;
        $this->to = $to;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employeeSales = EmployeeSale::query();
        $employeeSales = $employeeSales->with('employee');
        if ($this->from and $this->to and $this->from != "" and $this->to != "") {


            $employeeSales->whereBetween('sale_date',[$this->from,$this->to]);
        }

        if ( $this->employee_filter and  $this->employee_filter != "") {

            $employeeSales->where('employee_id', $this->employee_filter);
        }


        $employeeSales->when( $this->filter == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });


        return $employeeSales->get();

    }

    public function map($employeeSales): array
    {
        return [
            $employeeSales->employee->name,
            $employeeSales->amount,
            $employeeSales->remained,
            $employeeSales->sale_date,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","المبلغ","آجل","التاريخ"];
    }
}
