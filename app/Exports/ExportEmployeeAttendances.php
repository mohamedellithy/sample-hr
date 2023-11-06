<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployeeAttendances implements FromCollection,WithMapping ,WithHeadings
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
        $request = $this->request;

        $employeeAttendances = EmployeeAttendance::query();

        if ($this->request->has('datefilter')  and $this->request->get('datefilter') != "" ) {
            $result = explode('-',$this->request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $employeeAttendances->whereBetween('attendance_date',[$from,$to]);

        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {
            $employeeAttendances->where('employee_id',$request->get('employee_filter'));
        }

        if ($request->has('in') and $request->get('in') != "") {
            $employeeAttendances->where('clock_in', 'like', '%' .$request->get('in') . '%');
        }
        if ($request->has('out') and $request->get('out') != "") {
            $employeeAttendances->where('clock_out', 'like', '%' .$request->get('out') . '%');
        }


        $employeeAttendances->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('attendance_date', 'asc');
        },function ($q) {
            return $q->orderBy('attendance_date', 'desc');
        });

        return $employeeAttendances->get();

    }



    public function map($employeeAttendances): array
    {
        return [
            $employeeAttendances->employee->name,
            $employeeAttendances->attendance_date,
            $employeeAttendances->clock_in,
            $employeeAttendances->clock_out,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","التاريخ","الحضور","الانصراف"];
    }

}
