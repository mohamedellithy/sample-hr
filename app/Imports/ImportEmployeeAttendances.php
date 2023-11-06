<?php

namespace App\Imports;


use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Services\DeductionsAndOvertimeService;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEmployeeAttendances implements ToModel ,WithHeadingRow
{


    private $employees;
    protected $attendanceService;
    public function __construct(DeductionsAndOvertimeService $attendanceService)
    {
        $this->employees = Employee::select('id','name')->get();
        $this->attendanceService = $attendanceService;
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

                // delete EmployeeAttendance if exist
                $attendance= EmployeeAttendance::where('employee_id',$employee->id)->where('attendance_date',$this->transformDate($row['date']))->first();

                if($attendance){
                    $attendance->delete();
                }

                if (is_float($row['clock_in']) || is_double($row['clock_in'])) {
                    $row['clock_in'] =  Date::excelToDateTimeObject($row['clock_in'])->format('H:i:s');
                }
                if (is_float($row['clock_out']) || is_double($row['clock_out'])) {
                    $row['clock_out'] =  Date::excelToDateTimeObject($row['clock_out'])->format('H:i:s');
                }
                 // make request to send  to calculate Deductions And Overtime
                $request = new \Illuminate\Http\Request();
                $request->replace([
                'employee_id'=>$employee->id,
                'attendance_date'=>$this->transformDate($row['date']),
                'clock_in'=>$row['clock_in'],
                'clock_out'=>$row['clock_out']
            ]);


            $this->attendanceService->calculateDeductionsAndOvertime($request);

            //create new EmployeeAttendance
            return new EmployeeAttendance([
                'employee_id'=> $employee->id,
                'attendance_date'=>$this->transformDate($row['date']),
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
