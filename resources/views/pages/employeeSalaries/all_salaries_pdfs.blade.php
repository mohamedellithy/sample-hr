@php 
use NumberToWords\NumberToWords; 
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeSale;
use App\Models\EmployeeSalarie;
use App\Models\EmployeePaid;
@endphp
<html>
    <head>
        <meta name="google-site-verification" content="40aCnX7tt4Ig1xeLHMATAESAkTL2pn15srB14sB-EOs" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />    
    </head>
    <body>
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="text-align: center">
                            <p style="text-align: center">
                                <h4 style="color:black;font-size: 14px;font-weight: bolder">OPERA CAFEE</h4>
                                <h6 style="color:red;font-size: 14px;font-weight: bolder">SALARY SHEET FOR {{ date('F', mktime(0, 0, 0, $month, 10)); }} {{ $year }}</h6>
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-border inner-table" cellpadding="3">
                                    <thead>
                                        <tr class="inner-table" style="padding:10px;border:1px solid #171615;font-weight:600" height="25">
                                            <th rowspan="2" style="border:1px solid #171615;padding:10px">EMPLOYEES NAME</th>
                                            <th rowspan="2" style="border:1px solid #171615;padding:10px">BASIC SALARY</th>
                                            <th rowspan="2" style="border:1px solid #171615;">NO OF WORKING DAYS</th>
                                            <th rowspan="2" style="border:1px solid #171615;">OVER TIME</th>
                                            <th rowspan="2" style="border:1px solid #171615;">TOTAL SALARY</th>
                                            <th colspan="4" style="text-align: center;border:1px solid #171615;">DEDUCTION</th>
                                            <th rowspan="2" style="border:1px solid #171615;">NET SALARY</th>
                                            <th colspan="2" style="text-align: center;border:1px solid #171615;">NET SALARY</th>
                                        </tr>
                                        <tr class="inner-table" style="padding:10px;border:1px solid #171615;font-weight:600" height="25">
                                            <th style="border:1px solid #171615;">DEDUCTI ON FOR DELAY </th>
                                            <th style="border:1px solid #171615;">DEDUCTION FOR ADVANCE  </th>
                                            <th style="border:1px solid #171615;">OTHER DEDUCTION</th>
                                            <th style="border:1px solid #171615;">NET DEDUCTION</th>
                                            <th style="border:1px solid #171615;">Paid</th>
                                            <th style="border:1px solid #171615;">Pending</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach($employees as $employee)
                                            @php
                                                $employee->countAttends   =   EmployeeAttendance::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_attendances.attendance_date',$month)
                                                ->whereYear('employee_attendances.attendance_date',$year)->count() ?: 0;


                                                $employee->sumAdvances = EmployeeAdvance::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_advances.advance_date',$month)
                                                ->whereYear('employee_advances.advance_date',$year)->sum('amount');

                                                $employee->sumSales = EmployeeSale::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_sales.sale_date',$month)
                                                ->whereYear('employee_sales.sale_date',$year)->sum('remained');

                                                $employee->sumDeduction = EmployeeSalarie::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_salaries.date',$month)
                                                ->whereYear('employee_salaries.date',$year)->sum('deduction');

                                                $employee->sumOver_time = EmployeeSalarie::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_salaries.date',$month)
                                                ->whereYear('employee_salaries.date',$year)->sum('over_time');

                                                $employee->sumPaid = EmployeePaid::where([
                                                    'employee_id' => $employee->id
                                                ])->whereMonth('employee_paids.month',$month)
                                                ->whereYear('employee_paids.month',$year)->sum('paid');


                                                $employee->month_path =  $month;
                                                $employee->year_path  =  $year;

                                                $empolyee_salary = get_empolyee_price_by_month($employee,$month,$year);
                                                $monthName = date('F', mktime(0, 0, 0, $employee->month_path, 10));
                                                $calculateDayWage = (new App\Services\CalculateHourSalaryService())->calculateDayWage($empolyee_salary,$employee->countAttends);
                                                $net_salary       = 0;
                                                $over_time        = 0;
                                                $net_deduction    = 0;
                                            @endphp
                                            <tr style="border:1px solid gray;" height="25">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  $employee->name }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  $empolyee_salary }}
                                                    @php $net_salary = $empolyee_salary @endphp
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $employee->countAttends }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{   $over_time   = $employee->sumOver_time }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  abs($calculateDayWage) + $over_time }}
                                                    @php $net_salary = abs($calculateDayWage) + $over_time @endphp
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{   $deduction_delay = $employee->sumDeduction }}
                                                    @php $net_deduction  += $deduction_delay  @endphp
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  $deduction_advances = $employee->sumAdvances ?: 0 }}
                                                    @php $net_deduction     += $deduction_advances  @endphp
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  $deduction_sales   = $employee->sumSales ?: 0 }}
                                                    @php $net_deduction    += $deduction_sales  @endphp
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $net_deduction }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $net_salary = $net_salary - $net_deduction }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $employee->sumPaid ? round($employee->sumPaid,3): 0 }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px;">
                                                    {{ round($net_salary - $employee->sumPaid,3) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

