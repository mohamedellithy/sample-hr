<!-- Begin Page Content -->
@php 
use NumberToWords\NumberToWords; 
$monthName = date('F', mktime(0, 0, 0, $employee->month_path, 10));
@endphp
@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <br />
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    <form method="post" action="{{ route('admin.print-salary-invoice',[
                        'id' => $employee->id,
                        'month' => $employee->month_path.'-'.$employee->year_path
                    ]) }}">
                        @csrf
                        <button type="submit" class="btn btn-info">طباعة</button>
                    </form>
                </div>
                <div class="card-header" style="text-align: center">
                    <p style="text-align: center">
                        <h4 style="color:black">OPERA CAFEE</h4>
                        <h6 style="color:red">SALARY SHEET FOR {{ $monthName }} {{ $employee->year_path }}</h6>
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-border inner-table">
                            <thead>
                                 <tr class="inner-table">
                                     <th rowspan="2">EMPLOYEES NAME</th>
                                     <th rowspan="2">BASIC SALARY</th>
                                     <th rowspan="2">NO OF WORKING DAYS</th>
                                     <th rowspan="2">OVER TIME</th>
                                     <th rowspan="2">TOTAL SALARY</th>
                                     <th colspan="4" style="text-align: center;">DEDUCTION</th>
                                     <th rowspan="2">NET SALARY</th>
                                     <th colspan="2" style="text-align: center;">NET SALARY</th>
                                 </tr>
                                 <tr class="inner-table">
                                    <th>DEDUCTI ON FOR DELAY </th>
                                    <th>DEDUCTION FOR ADVANCE  </th>
                                    <th>OTHER DEDUCTION</th>
                                    <th>NET DEDUCTION</th>
                                    <th>Paid</th>
                                    <th>Pending</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $calculateDayWage = (new App\Services\CalculateHourSalaryService())->calculateDayWage($empolyee_salary,$employee->countAttends);
                                    $net_salary       = 0;
                                    $over_time        = 0;
                                    $net_deduction    = 0;
                                @endphp
                                <tr>
                                    <td>
                                        {{  $employee->name }}
                                    </td>
                                    <td>
                                        {{  $empolyee_salary }}
                                        @php $net_salary = $empolyee_salary @endphp
                                    </td>
                                    <td>
                                        {{ $employee->countAttends }}
                                    </td>
                                    <td>
                                        {{   $over_time   = $employee->sumOver_time }}
                                    </td>
                                    <td>
                                        {{  abs($calculateDayWage) + $over_time }}
                                        @php $net_salary = abs($calculateDayWage) + $over_time @endphp
                                    </td>
                                    <td>
                                        {{   $deduction_delay = $employee->sumDeduction }}
                                        @php $net_deduction  += $deduction_delay  @endphp
                                    </td>
                                    <td>
                                        {{  $deduction_advances = $employee->sumAdvances ?: 0 }}
                                        @php $net_deduction     += $deduction_advances  @endphp
                                    </td>
                                    <td>
                                        {{  $deduction_sales   = $employee->sumSales ?: 0 }}
                                        @php $net_deduction    += $deduction_sales  @endphp
                                    </td>
                                    <td>
                                        {{ $net_deduction }}
                                    </td>
                                    <td>
                                        {{ $net_salary = $net_salary - $net_deduction }}
                                    </td>
                                    <td>
                                        {{ $employee->sumPaid ? round($employee->sumPaid,3): 0 }}
                                    </td>
                                    <td>
                                        {{ round($net_salary - $employee->sumPaid,3) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if(($net_salary -$employee->sumPaid) != 0)
                        <form method="post" action="{{ route('admin.employee.add-salary',[
                            'employee_id' => $employee->id
                        ]) }}">
                            @csrf
                            <input name="monthes" type="hidden" value="{{ $employee->year_path.'-'.$employee->month_path.'-01' }}" />
                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="basic-default-company"> المبلغ المطلوب للدفع</label>
                                <input type="number" name="value" max="" step=".001" value="{{ round($net_salary - $employee->sumPaid,3) }}" class="form-control" placeholder="المبلغ" required>
                                @error('year')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm">
                                تسديد المبلغ المطلوب
                            </button>
                        </form>
                    @endif
                </div>
                @if($employee->sumPaid != 0)
                    <div class="card-footer">
                        <ul style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                            <li style="list-style:none;color:black;">
                                ص.ب445: <br/>
                                الرمز الربيدي: 214 <br/>
                                الخوير ، سلطنة عمان <br/>
                                نقال : 9
                            </li>

                            <li style="list-style:none">
                            <b style="font-weight: bolder;color:black;font-size: 24px;">OPERA CAFEE</b>
                            <br/>
                            <b style="font-weight: bolder;color:black;font-size: 24px;">اوبرا كافية</b>
                            </li>

                            <li style="list-style:none;text-align: left;color:black;">
                                P.O. BOX : 445  <br/>
                                P. C : 214 <br/>
                                Al-Khuwair, Sultanate of Oman <br/>
                                GSM: 95066781 <br/>
                            </li>
                        </ul>
                        <br/>
                        <ul>
                            <li style="list-style:none;text-align:center;color:black;">
                                <h4 style="color:black;">Initial Payment Voucher</h4>
                            </li>
                        </ul>
                        <ul style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                            <li style="list-style: none;text-decoration-line: underline;font-weight: bold;color:black;">
                                Date : {{ date('Y-m-d') }}
                            </li>

                            <li style="list-style:none;text-align: left;">
                            <table class="table" style="color:black !important;border:2px solid black">
                                <thead>
                                    <tr>
                                        <th style="color:black !important;border:2px solid black">بيسة </th>
                                        <th style="color:black !important;border:2px solid black">ريال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="color:black !important;border:2px solid black">
                                            {{ str_replace('0.','',($employee->sumPaid  - intval($employee->sumPaid))) }}
                                        </td>
                                        <td style="color:black !important;border:2px solid black">
                                            {{ intval($employee->sumPaid) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </li>
                        </ul>
                        <ul>
                            <li style="list-style: none;font-weight: bold;color:black;">
                                مدفوع الى السيد الفاضل
                                <strong style="padding-right:10%;color:rgb(104 104 104);">
                                    {{ $employee->name }}
                                </strong>
                                <label style="float:left">
                                    Paid to Mr./M/S:
                                </label>
                                <hr style="margin-top: 2px;height: 2px;color: #706f6f;"/>
                            </li>
                            <li style="list-style: none;font-weight: bold;color:black;">
                                مبلغ و قدرة ريال عمانى
                                <strong style="padding-right:10%;color:rgb(104 104 104);">
                                    @php
                                        $numberToWords = new NumberToWords();
                                        $numberTransformer = $numberToWords->getNumberTransformer('en');
                                        echo $numberTransformer->toWords($employee->sumPaid);
                                    @endphp
                                </strong>
                                <label style="float:left">
                                    :The Sum of Riyals Omani
                                </label>
                                <hr style="margin-top: 2px;height: 2px;color: #706f6f;"/>
                            </li>
                            <li style="list-style: none;font-weight: bold;color:black;">
                                و ذالك عن
                                <strong style="padding-right:10%;color:rgb(104 104 104);">
                                    salary of
                                    {{ $monthName }}
                                    {{ $employee->year_path }}
                                </strong>
                                <label style="float:left">
                                    : Being
                                </label>
                                <hr style="margin-top: 2px;height: 2px;color: #706f6f;"/>
                            </li>
                        </ul>
                        <p>تواريخ الدفعات</p>
                        <table cellpadding="6" width="100%">
                            <thead>
                                <tr style="border:2px solid #eee">
                                    <th style="border:2px solid #eee">المبلغ المدفوع</th>
                                    <th style="border:2px solid #eee">تاريخ الدفعة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee_payments as $employee_payment)
                                    <tr style="border:2px solid #eee">
                                        <td style="border:2px solid #eee">{{ formate_price($employee_payment->paid) }}</td>
                                        <td style="border:2px solid #eee">{{ $employee_payment->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br/>
                        <ul style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                            <li style="list-style: none;font-weight: bold;">
                                توقيع المستلم : ……………………………………
                            </li>

                            <li style="list-style:none;text-align: left;">
                                ………………………………: ccountant Signature
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@push('style')
<style>
    .inner-table , .inner-table td , .inner-table th{
        color:black !important;
    }
    .inner-table td , .inner-table th{
        border:1px solid gray !important;
    }
</style>
@endpush
