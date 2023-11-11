<!-- Begin Page Content -->
@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <br />
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>مرتبات الموظف</h5>
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
                                    <th>NET SALARY</th>
                                    <th>NET SALARY</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td>
                                    {{  $employeeSalary->name }}
                                    </td>
                                    <td>
                                    {{  $employeeSalary->salary }}
                                    </td>
                                    <td>12</td>
                                    <td>
                                        {{  formate_price($employeeSalary->sumOver_time )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->sumAdvances )}}
                                    </td>
    
                                    <td>
                                        {{  formate_price($employeeSalary->sumSales )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->sumDeduction )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->sumOver_time )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->salary + $employeeSalary->sumOver_time  - $employeeSalary->sumAdvances - $employeeSalary->sumSales - $employeeSalary->sumDeduction )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->salary + $employeeSalary->sumOver_time  - $employeeSalary->sumAdvances - $employeeSalary->sumSales - $employeeSalary->sumDeduction )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->salary + $employeeSalary->sumOver_time  - $employeeSalary->sumAdvances - $employeeSalary->sumSales - $employeeSalary->sumDeduction )}}
                                    </td>
                                    <td>
                                        {{  formate_price($employeeSalary->salary + $employeeSalary->sumOver_time  - $employeeSalary->sumAdvances - $employeeSalary->sumSales - $employeeSalary->sumDeduction )}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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