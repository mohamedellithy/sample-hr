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
                        <a href="{{ route('admin.expenses.create') }}" class="btn btn-success btn-sm" style="color:white">اضافة مصروف جديد</a>
                    </div>
                </div>
               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">
                        <div class="nav-item d-flex align-items-center m-2" style="background-color: #fff;padding: 2px;">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input type="text" class="search form-control border-0 shadow-none" onchange="document.getElementById('filter-data').submit()" placeholder="البحث ...." @isset($search) value="{{ $search }}" @endisset id="search" name="search" style="background-color:#fff;"/>
                        </div>

                        <div class="nav-item d-flex align-items-center m-2">

                            <input type="text" placeholder="تاريخ" onchange="document.getElementById('filter-data').submit()" class=" form-control"  @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>

                        </div>

                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="section" id="SelectSection" class="form-control">
                                <option value>تحديد القسم</option>
                                @foreach($main_departments as $main_department)
                                    <option value="{{ $main_department->id }}">{{ $main_department->department_name }}</option>
                                @endforeach
                            </select>
                            <select name="sub_service" id="Selectsub"  class="form-control">
                                <option value>تحديد البند</option>
                                @foreach($sub_departments as $sub_department)
                                    <option value="{{ $sub_department->id }}">{{ $sub_department->department_name }}</option>
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
                        <button type="submit" class="btn btn-primary btn-sm">بحث</button>
                    </form>
                    <form  method="post" action="{{ route('admin.expenses.export') }}">
                            @csrf
                            <div class="nav-item d-flex align-items-center m-2">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="datefilter" value="{{ $datefilter }}">
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <button type="submit" class="btn btn-primary btn-sm">تصدير</button>
                            </div>
                    </form>
                    </div>

           </div>
           <div class="table-responsive">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                           <th>رقم الفاتورة</th>
                            <th> المصروف </th>
                            <th> البند </th>
                            <th>المبلغ</th>
                            <th>مبلغ المدفوع</th>
                            <th>مبلغ الاجل</th>
                            <th>المورد</th>
                            <th>تاريخ الصرف</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>
                                   {{  $expense->bill_no }}
                                </td>
                                <td class="width-16">
                                        {{ $expense->department_main ? $expense->department_main->department_name : '' }}
                                </td>

                                <td class="width-16">
                                    {{ $expense->department_sub ? $expense->department_sub->department_name : '' }}
                            </td>

                                <td>
                                    {{ formate_price($expense->amount) }}
                                </td>
                                <td>
                                    {{ formate_price($expense->payments_sum_value) }}
                                </td>
                                <td>
                                    {{ formate_price($expense->amount - $expense->payments_sum_value) }}
                                </td>
                                <td>
                                    {{ $expense->supplier }}
                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $expense->expense_date }}
                                     </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a class="crud" title="تنزيل دفعات" href="{{ route('admin.expenses.payments',$expense->id) }}">
                                            <i class="fas fa-plus text-primary"></i>
                                        </a>
                                        <a class="crud" title="تفاصيل" href="{{ route('admin.expenses.show',$expense->id) }}">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.expenses.edit',$expense->id) }}" title="تعديل " class="crud edit-product" data-product-id="{{ $expense->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.expenses.destroy', $expense->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud" title="حذف" data-expense-service="{{ $expense->service }}">
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

    jQuery('#SelectSection').change(function(){
        let parent_id = jQuery(this).val();
        let url = "{{ route('admin.sub-departments',':parent_id') }}";
        url = url.replace(':parent_id',parent_id);
        jQuery('#Selectsub').html("");
        $.ajax({
            url:url,
            type:"GET",
            success: function(data){
                let option = "";
                data.sub_departments.forEach(function(item){
                    option +=`<option value="${item.id}">${item.department_name}</option>`;
                });
                jQuery('#Selectsub').html(option);
            }
        })
    });
</script>
@endpush
