<?php

namespace App\Imports;


use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEmployeeAttendances implements ToModel ,WithHeadingRow
{


    private $employees;
    private $errors = [];
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

         /* if (isset($row['employee'], $row['date'],$row['clock_in'],$row['clock_out'])){
 */
            $employee = $this->employees->where('name',$row['employee'])->first();
            if($employee){
                return new EmployeeAttendance([
                    'employee_id'=> $employee->id,
                    'attendance_date'=>$row['date'],
                    'clock_in'=>$row['clock_in'],
                    'clock_out'=>$row['clock_out'],

                ]);
            }
            return;

     /*    }else{
            return ;

        } */


    }


    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function onError(\Throwable $e)
    {

    }




}
