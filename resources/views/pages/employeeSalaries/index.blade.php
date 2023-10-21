@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$employee_filter = request()->query('employee_filter') ?: null;

@endphp
@section('content')

<div class="container-fluid">
    <br/>
    <!-- Basic Layout -->
    <form action="{{ route('admin.employeeSalaries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-11">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة ايام موظف </h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">الموظف</label>
                                    <select type="text" name="employee_id" class="form-control form-select2 selectProduct" required>
                                    <option value="">اختر الموظف</option>
                                    @foreach ($employees as $employee)
                                    <option value={{ $employee->id }}>{{ $employee->name }}</option>
                                    @endforeach
                                    </select>
                                @error('employee_id')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> الايام</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="days" min="0" value="{{ old('days') }}" required />
                                @error('days')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                
                        </div>



                        <button type="submit" class="btn btn-primary">اضافة</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">عرض مرتبات الموظفين</h5>
           <div class="card-header py-3 ">

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
                </form>
           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                           <th>#</th>
                            <th>الموظف</th>
                            <th>الايام</th>
                            <th>المرتب</th>
                            <th>اضافي</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($employeeSalaries as $employeeSalarie)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>

                                <td>
                                {{  $employeeSalarie->employee->name }}
                                </td>
                                <td>
                                    {{  $employeeSalarie->days }}
                                </td>
                                <td>
                                    {{  formate_price($employeeSalarie->employee->salary )}}
                                </td>
                                <td>
                                @if ($employeeSalarie->days > 30)
                                 {{  formate_price(($employeeSalarie->days - 30 ) * 24 * $employeeSalarie->employee->hour)}}
                                 @else
                                   <span class="badge bg-label-danger me-1">
                                  لا يوجد
                                     </span>
                                @endif

                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $employeeSalarie->created_at }}
                                     </span>
                                </td>

                                <td>
                                    <div class="d-flex">

                                        <a  class="crud edit-employeeSalarie" data-employeeSalarie-id="{{ $employeeSalarie->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.employeeSalaries.destroy', $employeeSalarie->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud">
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
               {{ $employeeSalaries->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>

jQuery('.edit-employeeSalarie').click(function(){
        let data_edit = jQuery(this).attr('data-employeeSalarie-id');
        let Popup = jQuery('#modalCenter').modal('show');
        let url = "{{ route('admin.employeeSalaries.edit',':id') }}";
        url = url.replace(':id',data_edit);
        $.ajax({
            url:url,
            type:"GET",
            success: function(data){
                if(data.status == true){
                    jQuery('#modal-content-inner').html(data.view);
                }
                console.log(data);
            }
        })
        console.log(Popup);
    });


   jQuery('.delete-item').click(function(){

       if(confirm('هل متأكد من اتمام حذف')){
           jQuery(this).parents('form').submit();
       }
   });
</script>
@endpush
