@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$employee_filter = request()->query('employee_filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
$in = request()->query('in') ?: null;
$out = request()->query('out') ?: null;
@endphp
@section('content')


<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">  شفتات الموظفين</h5>
           <div class="card-header py-3 ">
                 <div class="d-flex" style="flex-direction: row-reverse;">
                    <div class="nav-item d-flex align-items-center m-2">
                        <a href="{{ route('admin.shifts.create') }}" class="btn btn-success btn-sm" style="color:white">اضافة  شفت </a>
                    </div>
                </div>

               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">


                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="employee_filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control form-select2">
                                <option value="">فلتر الموظف</option>
                                @foreach (  $employees as  $employee)
                                    <option value="{{ $employee->id }}" @isset($employee_filter) @if ($employee_filter == $employee->id ) selected @endif @endisset>{{  $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="nav-item d-flex align-items-center m-2">

                            <input type="text" onchange="document.getElementById('filter-data').submit()" class=" form-control" placeholder="من - الي  " @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>

                        </div>

                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر المصروفات</option>
                                <option value="sort_asc" @isset($filter) @if ($filter=='sort_asc' ) selected @endif @endisset>الاقدم</option>
                                <option value="sort_desc" @isset($filter) @if ($filter=='sort_desc' ) selected @endif @endisset>الاحدث </option>
                            </select>
                        </div>

                                  <div class="nav-item d-flex align-items-center m-2">
                            <label style="padding: 0px 5px;color: #636481;">المعروض</label>
                            <select name="rows" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-sm">
                                    <option >10</option>
                                    <option value="50" @isset($rows) @if ($rows=='50' ) selected @endif @endisset>50</option>
                                    <option value="100" @isset($rows) @if ($rows=='100' ) selected @endif @endisset> 100</option>
                            </select>
                        </div>

                </div>
                      <div class="d-flex justify-content-between" style="background-color: #eee;">


                        <div class="nav-item d-flex align-items-center m-2">
                            <label style="color: #636481;">الحضور </label><br>&ensp;
                            <input type="time" onchange="document.getElementById('filter-data').submit()" class=" form-control" @isset($in) value="{{ $in }}" @endisset id="in" name="in"/>
                            &ensp;   &ensp;&ensp;
                                <label style="color: #636481;">الانصراف </label><br>&ensp;
                            <input type="time" onchange="document.getElementById('filter-data').submit()" class=" form-control" placeholder="ث : د : س" @isset($out) value="{{ $out }}" @endisset id="out" name="out"/>
                        </div>



                </form>

                <form  method="post" action="{{ route('admin.shifts.export') }}">
                  @csrf
                    <div class="nav-item d-flex align-items-center m-2">
                    <input type="hidden" name="employee_filter" value="{{ $employee_filter }}">
                        <input type="hidden" name="datefilter" value="{{ $datefilter }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        <input type="hidden" name="in" value="{{ $in }}">
                        <input type="hidden" name="out" value="{{ $out }}">
                        <a href="{{ route('admin.shifts.index') }}"class="btn btn-danger btn-sm">reset</a>   &ensp;&ensp;
                        <button type="submit" class="btn btn-primary btn-sm">تصدير</button>
                    </div>
                </form>
                </div>

           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                           <th>#</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>

                            <th>الحضور</th>
                            <th>الانصراف</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($shifts as $shift)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>

                                <td>
                                {{  $shift->employee->name }}
                                </td>
                                <td>
                                    {{  $shift->date }}
                                </td>

                                <td>
                                    {{ formate_time2($shift->clock_in) }}
                                </td>
                                <td>
                                    {{  formate_time2($shift->clock_out )}}
                                </td>

                                        <td>
                                    <div class="d-flex">

                                        <a href="{{ route('admin.shifts.edit',$shift->id) }}" class="crud edit-product" data-product-id="{{ $shift->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.shifts.destroy', $shift->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud" data-employee-name="{{ $shift->employee->name }}">
                                                <i class="fas fa-trash-alt  text-danger"></i>
                                            </a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                   </tbody>
               </table>
           </div>
           <br/><br/>
           <div class="d-flex flex-row justify-content-center">
               {{ $shifts->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>
 jQuery('.delete-item').click(function(){
       let employee_name = jQuery(this).attr('data-employee-name');
       if(confirm('هل متأكد من اتمام حذف  '+ employee_name)){
           jQuery(this).parents('form').submit();
       }
   });
</script>
@endpush
