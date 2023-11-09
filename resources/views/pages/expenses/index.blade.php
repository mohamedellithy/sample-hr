@extends('layouts.master')
@php
$search = request()->query('search') ?: null;
$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$filter_salary = request()->query('filter_salary') ?: null;
$datefilter = request()->query('datefilter') ?: null;

$service_filter = request()->query('service_filter') ?: null;
@endphp
@section('content')
<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">عرض المصروفات</h5>
           <div class="card-header py-3 ">
                <div class="d-flex" style="flex-direction: row-reverse;">
                    <div class="nav-item d-flex align-items-center m-2">
                        <a href="{{ route('admin.expenses.create') }}" class="btn btn-success btn-md" style="color:white">اضافة مصروف جديد</a>
                    </div>
                </div>
               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">
                {{--         <div class="nav-item d-flex align-items-center m-2" style="background-color: #fff;padding: 2px;">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input type="text" class="search form-control border-0 shadow-none" onchange="document.getElementById('filter-data').submit()" placeholder="البحث ...." @isset($search) value="{{ $search }}" @endisset id="search" name="search" style="background-color:#fff;"/>
                        </div> --}}


                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="service_filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر الخدمه</option>
                                <option value="بار" @isset($service_filter) @if ($service_filter=='بار' ) selected @endif @endisset>بار</option>
                                <option value="شيشه" @isset($service_filter) @if ($service_filter=='شيشه' ) selected @endif @endisset>شيشه</option>
                                <option value="صيانه" @isset($service_filter) @if ($service_filter=='صيانه' ) selected @endif @endisset>صيانه</option>
                                <option value="مطبخ" @isset($service_filter) @if ($service_filter=='مطبخ' ) selected @endif @endisset>مطبخ</option>
                                <option value="owner" @isset($service_filter) @if ($service_filter=='owner' ) selected @endif @endisset>owner</option>
                            </select>
                        </div>

                        <div class="nav-item d-flex align-items-center m-2">

                            <input type="text" onchange="document.getElementById('filter-data').submit()" class=" form-control"  @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>

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
                    <form  method="post" action="{{ route('admin.expenses.export') }}">
                            @csrf
                            <div class="nav-item d-flex align-items-center m-2">
                            <input type="hidden" name="service_filter" value="{{ $service_filter }}">
                            <input type="hidden" name="datefilter" value="{{ $datefilter }}">
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
                            <th>نوع المصروف </th>
                            <th>الشركه</th>
                            <th>المبلغ</th>
                            <th>تاريخ الصرف</th>
                            <th>المرفق</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>
                                   {{ $loop->index  + 1}}
                                </td>

                                <td class="width-16">
                                        {{ $expense->service }}
                                </td>
                                 <td class="width-16">
                                        {{ $expense->company }}
                                </td>
                                <td>
                                    {{ $expense->amount }}
                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $expense->expense_date }}
                                     </span>
                                </td>
                                   <td>
                                   @if ($expense->attachment)
                                     <img src="{{$expense->attachment }}">
                                     @else
                                        <span class="badge bg-label-danger me-1">
                                 لم يتم اضافه
                                     </span>
                                   @endif

                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a class="crud" href="{{ route('admin.expenses.show',$expense->id) }}">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.expenses.edit',$expense->id) }}" class="crud edit-product" data-product-id="{{ $expense->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.expenses.destroy', $expense->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud" data-expense-service="{{ $expense->service }}">
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
               {{ $expenses->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')

<script>

   jQuery('.delete-item').click(function(){
       let expense_service = jQuery(this).attr('data-expense-service');
       if(confirm('هل متأكد من اتمام حذف المصروف '+ expense_service)){
           jQuery(this).parents('form').submit();
       }
   });
</script>
@endpush
