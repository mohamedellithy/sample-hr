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
                                <h4 style="color:black;font-size: 18px;font-weight: bolder">OPERA CAFEE</h4>
                                <h6 style="color:red;font-size: 18px;font-weight: bolder">SALARY SHEET FOR {{ $employeeSalary->attendances_date }}</h6>
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
                                        @php
                                            $calculateDayWage = (new App\Services\CalculateHourSalaryService())->calculateDayWage($employeeSalary->salary,$employeeSalary->countAttends);
                                            $net_salary       = 0;
                                            $over_time        = 0;
                                            $net_deduction    = 0;
                                        @endphp
                                        <tr style="border:1px solid gray;" height="25">
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{  $employeeSalary->name }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{  $employeeSalary->salary }}
                                                @php $net_salary += $employeeSalary->salary @endphp
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{ $employeeSalary->countAttends }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{   $over_time   = $employeeSalary->sumOver_time + abs($calculateDayWage > 0 ? $calculateDayWage : 0) }}
                                                @php $net_salary += $over_time @endphp
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{  $net_salary }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{   $deduction_delay = $employeeSalary->sumDeduction + abs($calculateDayWage < 0 ? $calculateDayWage : 0) }}
                                                @php $net_deduction  += $deduction_delay  @endphp
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{  $deduction_advances = $employeeSalary->sumAdvances ?: 0 }}
                                                @php $net_deduction     += $deduction_advances  @endphp
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{  $deduction_sales   = $employeeSalary->sumSales ?: 0 }}
                                                @php $net_deduction    += $deduction_sales  @endphp
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{ $net_deduction }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{ $net_salary = $net_salary - $net_deduction }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                {{ $employeeSalary->sumPaid ?: 0 }}
                                            </td>
                                            <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px;">
                                                {{ $net_salary - $employeeSalary->sumPaid }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if(!isset($employeeSalary->sumPaid))
                                <form method="post" action="{{ route('admin.employee.add-salary',[
                                    'employee_id' => $employeeSalary->id
                                ]) }}">
                                    @csrf
                                    <input name="monthes" type="hidden" value="{{ $employeeSalary->year_path.'-'.$employeeSalary->month_path.'-01' }}" />
                                    <input name="value" type="hidden" value="{{ $net_salary - $employeeSalary->sumPaid }}" />
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        تسديد المرتب
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if($employeeSalary->sumPaid)
                            <div class="card-footer">
                                <table style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                                    <tr>
                                        <td style="display:inline-block;list-style:none;color:black;">
                                            ص.ب445: <br/>
                                            الرمز الربيدي: 214 <br/>
                                            الخوير ، سلطنة عمان <br/>
                                            نقال : 9
                                        </td>
                                        <td style="display:inline-block;list-style:none">
                                            <b style="font-weight: bolder;color:black;font-size: 12px;">OPERA CAFEE</b>
                                            <br/>
                                            <b style="font-weight: bolder;color:black;font-size: 12px;">اوبرا كافية</b>
                                        </td>
                                        <td style="display:inline-block;list-style:none;text-align: left;color:black;">
                                            P.O. BOX : 445  <br/>
                                            P. C : 214 <br/>
                                            Al-Khuwair, Sultanate of Oman <br/>
                                            GSM: 95066781 <br/>
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                                <ul>
                                    <li style="list-style:none;text-align:center;color:black;">
                                        <h4 style="color:black;">Initial Payment Voucher</h4>
                                    </li>
                                </ul>
                                <table style="display: flex;flex-wrap: nowrap;justify-content: space-between;color:black !important;">
                                    <tr>
                                        <td style="list-style: none;text-decoration-line: underline;font-weight: bold;color:black !important;" width="80%">
                                            Date : {{ date('Y-m-d') }}
                                        </td>
                                        <td style="list-style:none;text-align: left;color:black !important;" width="20%">
                                            <table class="table" style="color:black !important;border:2px solid black" cellpadding="4">
                                                <thead>
                                                    <tr style="color:black !important;">
                                                        <th style="color:black !important;border:2px solid black">بيسة </th>
                                                        <th style="color:black !important;border:2px solid black">عمانى</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="color:black !important;">
                                                        <td style="color:black !important;border:2px solid black">
                                                            {{ $employeeSalary->sumPaid  - intval($employeeSalary->sumPaid)}}
                                                        </td>
                                                        <td style="color:black !important;border:2px solid black">
                                                            {{ intval($employeeSalary->sumPaid) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table cellpadding="6"> 
                                    <tr style="list-style: none;font-weight: bold;color:black;">
                                        <td>
                                            مدفوع الى السيد الفاضل   
                                        </td>
                                        <td style="padding-right:10%;color:black;">
                                            {{ $employeeSalary->name }}
                                        </td>
                                        <td>
                                            <label style="float:left">
                                                Paid to Mr./M/S: 
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="list-style: none;font-weight: bold;color:black;">
                                        <td>
                                            مبلغ و قدرة ريال عمانى
                                        </td>
                                        <td style="padding-right:10%;color:black;">
                                            {{ $employeeSalary->name }}
                                        </td>
                                        <td>
                                            <label style="float:left">
                                                :The Sum of Riyals Omani
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="list-style: none;font-weight: bold;color:black;">
                                        <td>
                                            و ذالك عن 
                                        </td>
                                        <td style="padding-right:10%;color:black;">
                                            salary of {{ $employeeSalary->attendances_date }}
                                        </td>
                                        <td>
                                            <label style="float:left">
                                                : Being
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                                <br/><br/><br/>
                                <table style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                                    <tr>
                                        <td style="list-style: none;font-weight: bold;">
                                            توقيع المستلم : ……………………………………
                                        </td>
                                        <td style="list-style:none;text-align: left;">
                                            ………………………………: ccountant Signature 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

