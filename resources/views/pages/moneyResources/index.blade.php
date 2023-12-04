@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
$types_resources = [
    'balance'           => 'الرصيد السابق',
    'bank_withdraw'     => 'سحب كاش من البنك',
    'outgoing_resource' => 'مصدر خارجي',
    'sales'             => 'مبيعات',
    'client_payments_sales' => 'تحصيل مدفوعات مبيعات عميل'
];

$total_resource = \App\Models\MoneyResource::sum('value');
$total_advances = \App\Models\EmployeeAdvance::sum('amount');
$total_expenses = \App\Models\ExpensesPayment::sum('value');

if(request()->has('datefilter') and request()->get('datefilter') != ""):
    $result = explode('-',request()->get('datefilter'));
    $from   = \Carbon\Carbon::parse($result[0])->format('Y-m-d');
    $to     = \Carbon\Carbon::parse($result[1])->format('Y-m-d');
    $total_resource = \App\Models\MoneyResource::whereBetween('resource_date',[$from,$to])->sum('value');
    $total_advances = \App\Models\EmployeeAdvance::whereBetween('advance_date',[$from,$to])->sum('amount');
    $total_expenses = \App\Models\ExpensesPayment::whereBetween('created_at',[$from,$to])->sum('value');
endif;


@endphp

@section('content')

<div class="container-fluid">
    <br/>
    <!-- Basic Layout -->
    <form action="{{ route('admin.money-resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة مورد جديد</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">كاش</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="value" min="0" step=".001" value="{{ old('value') }}" required />
                                @error('value')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-default-fullname">المصدر</label>
                                <select name="type" class="form-control form-select2 selectProduct" required>
                                    <option value>اختر المصدر</option>
                                    <option value="balance">رصيد سابق</option>
                                    <option value="bank_withdraw">كاش من البنك</option>
                                    <option value="outgoing_resource">مصادر خارجية</option>
                                    <option value="sales">مبيعات</option>
                                </select>
                                @error('type')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">تاريخ المصدر</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="resource_date" value="{{ old('resource_date') }}" max="{{ date('Y-m-d') }}" required />
                                @error('resource_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-default-fullname">البيان</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    name="description"   value="{{ old('description') }}" />
                                @error('description')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">اضافة مبالغ مالية</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <ul style="display: block"> 
                                <li style="list-style: none;background-color:#fce4ec;padding: 17px;margin-left: 14px;">
                                    <p> اجمالى المتبقي من الموارد</p>
                                    <strong>{{ formate_price($total_resource - $total_advances - $total_expenses) }}</strong>
                                </li>
                            </ul>
                            <ul style="display: flex;flex-wrap: wrap;justify-content: flex-start;;align-items: center;">
                                <li style="list-style: none;background-color: #e0f2f1;padding: 17px;margin-left: 14px;">
                                    <p>اجمالى الموارد</p>
                                    <strong>{{ formate_price($total_resource) }}</strong>
                                </li>
                                <li style="list-style: none;background-color: #e8eaf6;padding: 17px;margin-left: 14px;">
                                    <p>اجمالى السلف</p>
                                    <strong>{{ formate_price($total_advances) }}</strong>
                                </li>
                                <li style="list-style: none;background-color: #fbe9e7;padding: 17px;margin-left: 14px;">
                                    <p>اجمالى المصروفات</p>
                                    <strong>{{ formate_price($total_expenses) }}</strong>
                                </li>
                            </ul>
                        </div>
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
           <h5 class="card-header">عرض الموارد المالية</h5>
           <div class="card-header py-3 ">

               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">

                        <div class="nav-item d-flex align-items-center m-2">
                            <input type="text" placeholder="التاريخ" onchange="document.getElementById('filter-data').submit()" class=" form-control"  @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>
                        </div>

                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر المبيعات</option>
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
                <form  method="post" action="{{ route('admin.money-resources.export') }}">
                  @csrf
                            <div class="nav-item d-flex align-items-center m-2">
                             <input type="hidden" name="datefilter" value="{{ $datefilter }}">
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <button type="submit" class="btn btn-primary btn-sm">تصدير</button>
                            </div>
                </form>
                    </div>



           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                            <th></th>
                            <th>التاريخ</th>
                            <th>المبلغ الوارد</th>
                            <th>المصدر</th>
                            <th>البيان</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach($resources as $resource)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <span class="badge bg-label-primary me-1">
                                   {{ $resource->resource_date }}
                                    </span>
                                </td>
                                <td>
                                {{  formate_price($resource->value) }}
                                </td>
                                
                                <td>
                                    {{  isset($types_resources[$resource->type]) ? $types_resources[$resource->type] : '-' }}
                                    @if($resource->type == 'client_payments_sales')
                                        <a href="{{ route('admin.client-payments.get',['client_id' => $resource->reference_id]) }}">
                                            {{ \App\Models\Client::find($resource->reference_id)->name }}
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    {{ $resource->description  }}
                                </td>
                                <td>
                                    <div class="d-flex">

                                        <a  class="crud edit-sale" data-moeny-resource-id="{{ $resource->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.money-resources.destroy', $resource->id) }}">
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
               {{ $resources->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>

jQuery('.edit-sale').click(function(){
        let data_edit = jQuery(this).attr('data-moeny-resource-id');
        let Popup = jQuery('#modalCenter').modal('show');
        let url = "{{ route('admin.money-resources.edit',':id') }}";
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
