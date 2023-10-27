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
    <form action="{{ route('admin.employeeSalaries.export') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-11">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة ايام موظف </h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-3">
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

                             <div class="mb-3 col-md-3">
                                <label class="form-label" for="basic-default-company"> الاسبوع</label>
                                  <select type="text" name="week" class="form-control selectProduct" required>
                                    <option value="">اختر الاسبوع</option>
                                    <option value="1">الاول</option>
                                    <option value="2">الثاني</option>
                                    <option value="3">الثالث</option>
                                    <option value="4">الرابع</option>
                                    </select>
                                @error('week')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="basic-default-company"> الشهر</label>
                                  <select type="text" name="month" class="form-control selectProduct form-select2 " required>
                                    <option value="">اختر الشهر</option>
                                    <option value="1">1 - يناير</option>
                                    <option value="2">2 - فبراير</option>
                                    <option value="3">3 - مارس</option>
                                    <option value="4">4 - ابريل</option>
                                    <option value="5">5 - مايو</option>
                                    <option value="6">6 - يونيو</option>
                                    <option value="7">7 - يوليو</option>
                                    <option value="8">8 - اغسطس</option>
                                    <option value="9">9 - سبتمبر</option>
                                    <option value="10">10 - اكتوبر</option>
                                    <option value="11">11 - نوفمبر</option>
                                    <option value="12">12 - ديسمبر</option>
                                    </select>
                                @error('month')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                                    <div class="mb-3 col-md-3">
                                <label class="form-label" for="basic-default-company"> السنه</label>
                             <input type="number" min="2000" max="2050" name="year"value=""class="form-control"title="ادخل سنه مابين 2000-2050" placeholder="ادخل السنه">
                                @error('year')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">تصدير</button>
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
                        </form>
           {{--      <form  method="post" action="{{ route('admin.employeeSalaries.export') }}">
                  @csrf
                    <div class="nav-item d-flex align-items-center m-2">
                        <input type="hidden" name="employee_id" value="{{ $employee_filter }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        <button type="submit" class="btn btn-primary">export</button>
                    </div>
                </form> --}}
                    </div>

           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                           <th>#</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            <th>السلف</th>
                            <th>آجل مبيعات</th>
                            <th>الخصومات</th>
                            <th>الاضافي</th>
                            <th>المجموع</th>
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
                                 <span class="badge bg-label-primary me-1">
                                    {{  $employeeSalarie->date }}
                                     </span>
                                </td>
                                <td>
                                    {{  formate_price($employeeSalarie->advances )}}
                                </td>
                                 <td>
                                    {{  formate_price($employeeSalarie->sales )}}
                                </td>
                                 <td>
                                    {{  formate_price($employeeSalarie->deduction )}}
                                </td>
                                  <td>
                                    {{  formate_price($employeeSalarie->over_time )}}
                                </td>
                                <td>
            {{  formate_price($employeeSalarie->advances + $employeeSalarie->sales+ $employeeSalarie->deduction )}}
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


$(() => {
  $('#date').datepicker({ dateFormat: 'mm-yy' });
});

</script>
@endpush
