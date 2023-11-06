@extends('layouts.master')
@php
$search = request()->query('search') ?: null;
$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$filter_salary = request()->query('filter_salary') ?: null;
$export =null;

@endphp
@section('content')
<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">عرض الموظفيين</h5>
           <div class="card-header py-3 ">
                <div class="d-flex" style="flex-direction: row-reverse;">
                    <div class="nav-item d-flex align-items-center m-2">
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-success btn-md" style="color:white">اضافة موظف جديد</a>
                    </div>
                </div>
               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">
                        <div class="nav-item d-flex align-items-center m-2" style="background-color: #fff;padding: 2px;">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input type="text" class="search form-control border-0 shadow-none" placeholder="البحث ...." @isset($search) value="{{ $search }}" @endisset id="search" name="search" style="background-color:#fff;"/>
                        </div>

                  {{--       <div class="nav-item d-flex align-items-center m-2">
                            <select name="filter_salary" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر المرتب</option>
                                <option value="sort_desc" @isset($filter_salary) @if ($filter_salary=='sort_desc' ) selected @endif @endisset>الاعلي </option>
                                <option value="sort_asc" @isset($filter_salary) @if ($filter_salary=='sort_asc' ) selected @endif @endisset>الاقل</option>
                            </select>
                        </div> --}}

                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="filter" id="largeSelect" onclick="myFunction2()" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر الموظفين</option>
                                <option value="sort_desc" @isset($filter) @if ($filter=='sort_desc' ) selected @endif @endisset>الاحدث </option>
                                <option value="sort_asc" @isset($filter) @if ($filter=='sort_asc' ) selected @endif @endisset>الاقدم</option>
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
                    </form>
                    <form  method="post" action="{{ route('admin.employees.export') }}">
                            @csrf
                            <div class="nav-item d-flex align-items-center m-2">
                             <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <button type="submit" class="btn btn-primary">تصدير</button>
                            </div>
                    </form>
                </div>


           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                           <th>#</th>
                            <th>الاسم </th>
                            <th>الجنسيه</th>
                            <th>المرتب</th>
                          <th>تاريخ الانضمام</th>
                              <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($employees as $employee)
                            <tr>
                                <td>
                                   {{ $loop->index  + 1}}
                                </td>

                                <td class="width-16">
                                    <a class="crud" href="{{ route('admin.employees.show', $employee->id) }}">
                                        {{ $employee->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $employee->nationality }}
                                </td>
                                <td>
                                    {{ formate_price($employee->salary) }}
                                </td>
                                    <td>
                                    {{ $employee->join_date }}
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <a class="crud" href="{{ route('admin.employees.show',$employee->id) }}">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.employees.edit',$employee->id) }}" class="crud edit-product" data-product-id="{{ $employee->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.employees.destroy', $employee->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud" data-employee-name="{{ $employee->name }}">
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
               {{ $employees->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>

   jQuery('.delete-item').click(function(){
       let employee_name = jQuery(this).attr('data-employee-name');
       if(confirm('هل متأكد من اتمام حذف الموظف '+ employee_name)){
           jQuery(this).parents('form').submit();
       }
   });




</script>
@endpush
