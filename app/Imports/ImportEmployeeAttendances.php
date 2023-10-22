<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportEmployeeAttendances implements ToModel ,WithHeadingRow
{



    private $employees;

    public function __construct()
    {
        $this->employees = Employee::select('id','name')->get();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {


        $employee = $this->employees->where('name',$row['employee'])->first();
        if($employee){
            return new EmployeeAttendance([
                'employee_id'=> $employee->id,
                'attendance_date'=>$row['date'],
                'clock_in'=>$row['clock_in'],
                'clock_out'=>$row['clock_out'],


               /*  'attendance_date'=>Carbon::instance(Date::excelToDateTimeObject($row['date']))->format('Y-m-d'), */

            /*     'clock_in'=>Carbon::instance(Date::excelToDateTimeObject($row['clockin']))->format('H:i:s'),

                'clock_out'=>Carbon::instance(Date::excelToDateTimeObject($row['clockout']))->format('H:i:s'), */
            ]);
        }
        return;

    }
}
