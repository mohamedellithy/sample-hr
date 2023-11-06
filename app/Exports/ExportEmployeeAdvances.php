<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EmployeeAdvance;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployeeAdvances implements FromCollection,WithMapping ,WithHeadings
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
        $employeeAdvances = EmployeeAdvance::query();
        $employeeAdvances= $employeeAdvances->with('employee');


        if ($this->request->has('datefilter')  and $this->request->get('datefilter') != "" ) {

            $result = explode('-',$this->request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $employeeAdvances->whereBetween('advance_date',[$from,$to]);

        }

        if ($this->request->has('employee_filter') and $this->request->get('employee_filter') != "") {

            $employeeAdvances->where('employee_id',$this->request->get('employee_filter'));
        }


        $employeeAdvances->when($this->request->filter == 'sort_asc', function ($q) {
            return $q->orderBy('advance_date', 'asc');
        },function ($q) {
            return $q->orderBy('advance_date', 'desc');
        });

        return $employeeAdvances->get();
    }


    public function map($employeeAdvances): array
    {
        return [
            $employeeAdvances->employee->name,
            $employeeAdvances->amount,
            $employeeAdvances->advance_date,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","المبلغ","التاريخ"];
    }
}
