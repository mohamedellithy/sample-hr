<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Employee;
use App\Mail\VerifyCodeMail;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use Illuminate\Support\Facades\Mail;
use App\Services\CalculateHourSalaryService;

class DeductionsAndOvertimeService
{

    protected $CalcSalSE;

    public function __construct(CalculateHourSalaryService $CalcSalSE)
    {
        $this->CalcSalSE = $CalcSalSE;

    }


    function calculateDeductionsAndOvertime(Request $request) {

        $employee = Employee::find($request->employee_id);
        $shift    = Shift::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

        if($shift){

            $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();
            if($salary){
                $salary->deduction = 0;
                $salary->over_time = 0;
                $salary->save();
            }

            $shift_clock_in = Carbon::parse($shift->clock_in);
            $shift_clock_out = Carbon::parse($shift->clock_out);

            $attendance_clock_in = Carbon::parse($request->clock_in)->format('H:i:s');
            $attendance_clock_out = Carbon::parse($request->clock_out)->format('H:i:s');



            $BasicWorkHours = $shift_clock_out->diffInHours($shift_clock_in);
           //$WorkHours = $attendance_clock_in->diffInHours($attendance_clock_out);

            $clock_in = $shift_clock_in->diffInHours($attendance_clock_in);
            $clock_out = $shift_clock_out->diffInHours($attendance_clock_out);

            $clock_in_Minutes = $shift_clock_in->diffInMinutes($attendance_clock_in);
            $clock_out_Minutes = $shift_clock_out->diffInMinutes($attendance_clock_out);

            if($clock_in_Minutes > 15 ){

                if($shift_clock_in->hour > 12){            // PM

                    if( $shift_clock_in->format('H:i:s') < $attendance_clock_in){   // deduction

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $deductionAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->deduction += round($clock_in_Minutes/60 * $deductionAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'deduction'=> round($clock_in_Minutes/60 * $deductionAmount),
                            ]);
                        }

                    } if($shift_clock_in->format('H:i:s') > $attendance_clock_in){       // over time

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $overTimeAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->over_time += round($clock_in_Minutes/60 * $overTimeAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'over_time'=>  round($clock_in_Minutes/60 * $overTimeAmount),
                            ]);
                        }

                    }

                }
            }

            if($clock_out_Minutes > 15){
                if($shift_clock_out->hour > 12){        // PM

                    if( $shift_clock_out->format('H:i:s') > $attendance_clock_out){   // deduction

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $deductionAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->deduction +=  round($clock_out_Minutes/60 * $deductionAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'deduction'=> round($clock_out_Minutes/60 * $deductionAmount),
                            ]);
                        }

                    }if($shift_clock_out->format('H:i:s') < $attendance_clock_out){       // over time

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $overTimeAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->over_time +=  round($clock_out_Minutes/60 * $overTimeAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'over_time'=>  round($clock_out_Minutes/60 * $overTimeAmount),
                            ]);
                        }

                    }

                }

            }

            if($clock_in_Minutes > 15){
                if($shift_clock_in->hour < 12){         // AM

                    if( $shift_clock_in->format('H:i:s') < $attendance_clock_in){   // deduction

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $deductionAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->deduction +=round($clock_in_Minutes/60 * $deductionAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'deduction'=> round($clock_in_Minutes/60 * $deductionAmount),
                            ]);
                        }

                    }if($shift_clock_in->format('H:i:s') > $attendance_clock_in){       // over time

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $overTimeAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->over_time +=  round($clock_in_Minutes/60 * $overTimeAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'over_time'=>  round($clock_in_Minutes/60 * $overTimeAmount),
                            ]);
                        }

                    }
                }
            }

            if($clock_out_Minutes > 15){
                if($shift_clock_out->hour < 12){      // AM

                    if( $shift_clock_out->format('H:i:s') > $attendance_clock_out){   // deduction

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $deductionAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->deduction +=  round($clock_out_Minutes/60 * $deductionAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'deduction'=> round($clock_out_Minutes/60 * $deductionAmount),
                            ]);
                        }

                    }if($shift_clock_out->format('H:i:s') < $attendance_clock_out){       // over time

                        $salary= EmployeeSalarie::where('employee_id',$request->employee_id)->where('date',$request->attendance_date)->first();

                        $overTimeAmount = $this->CalcSalSE->calculateHourlyWage($employee->salary,$BasicWorkHours);

                        if($salary){
                            $salary->over_time +=  round($clock_out_Minutes/60 * $overTimeAmount);
                            $salary->save();
                        }else{
                            EmployeeSalarie::create([
                                'employee_id'=>$request->employee_id,
                                'date'=>$request->attendance_date,
                                'over_time'=>  round($clock_out_Minutes/60 * $overTimeAmount),
                            ]);
                        }

                    }
                }

            }

            return TRUE;

        }

    }


}
