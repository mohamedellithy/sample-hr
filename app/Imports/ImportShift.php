<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;



class ImportShift implements ToCollection
{

    private $employees;

    public function __construct()
    {
        $this->employees = Employee::select('id','name')->get();
    }


    public function collection(Collection $rows)
    {        
        $ros = [];
        foreach($rows as $row){
            $ros[] = trim($row[1]);
           if(isset($row[1])){
            $employee = $this->employees->where('name',trim($row[1]))->first();
            if($employee){
                for ($x = 2; $x <= 8; $x++) {
                        if(isset($row[$x])){

                            // delete old Shift if exist
                            $Shift= Shift::where('employee_id',$employee->id)->where('date',$this->transformDate($rows[2][$x]))->first();
                            if($Shift){
                                $Shift->delete();
                            }

                            Shift::create([
                                'employee_id'=>$employee->id,
                                'date'=>$this->transformDate($rows[2][$x]),
                                'clock_in'=>formate_time($row[$x],0),
                                'clock_out'=>formate_time($row[$x],1),
                            ]);
                        }
                    }
                }
            }
        }
    }


    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
