@extends('Theme_2.layouts.master')
@php $search = request()->query('search') ?: null;
$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null; @endphp
@section('content')
<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">عرض المرتجعات</h5>
           <div class="card-header py-3 ">
                <div class="d-flex" style="flex-direction: row-reverse;">
                    <div class="nav-item d-flex align-items-center m-2">
                        <a href="{{ route('admin.returns.create') }}" class="btn btn-success btn-md" style="color:white">اضافة مرتجع جديدة</a>
                    </div>
                </div>
               <form id="filter-data" method="get" class="d-flex justify-content-between">
                   <div class="nav-item d-flex align-items-center m-2" style="background-color: #eee;padding: 8px;">
                       <i class="bx bx-search fs-4 lh-0"></i>
                       <input type="text" class="search form-control border-0 shadow-none" placeholder="البحث ...." @isset($search) value="{{ $search }}" @endisset id="search" name="search" style="background-color: #eee;"/>
                   </div>
                   <div class="d-flex">
                       <div class="nav-item d-flex align-items-center m-2">
                           <select name="filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option>فلتر الاصناف</option>
                                <option value="high-price" @isset($filter) @if ($filter=='high-price' ) selected @endif
                                    @endisset>
                                    الاعلي سعرا</option>
                                <option value="low-price" @isset($filter) @if ($filter=='low-price' ) selected @endif
                                    @endisset>
                                    الاقل سعرا</option>

                            </select>
                       </div>
                       <div class="nav-item d-flex align-items-center m-2">
                           <label style="padding: 0px 10px;color: #636481;">المعروض</label>
                           <select name="rows" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-sm">
                                <option>10</option>
                                <option value="50" @isset($rows) @if ($rows=='50' ) selected @endif @endisset>
                                    50</option>
                                <option value="100" @isset($rows) @if ($rows=='100' ) selected @endif @endisset>
                                    100</option>
                            </select>
                       </div>
                   </div>
               </form>
           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                            <th>كود المرتجع</th>
                            <th>العميل</th>
                            <th>اجمالى سعر المرتجع</th>
                            <th>تاريخ المرتجع</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($customerReturns as $customerReturn)
                            <tr>
                                <td class="width-16">
                                    <strong>
                                        {{ $customerReturn->order_number }}#
                                    </strong>
                                </td>
                                <td class="width-16">
                                    <a class="crud" href="{{ route('admin.returns.show', $customerReturn->id) }}">
                                        {{ $customerReturn->customer ? $customerReturn->customer->name : '-' }}
                                    </a>
                                </td>
                                <td>
                                    {{ $customerReturn->discount }} USD
                                </td>
                                <td>
                                    {{ $customerReturn->total_price }} USD
                                </td>
                                <td>
                                    <span class="badge bg-label-primary me-1">
                                        {{ $customerReturn->created_at }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a class="crud" href="{{ route('admin.returns.show',$customerReturn->id) }}">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.returns.edit',$customerReturn->id) }}" class="crud edit-product" data-product-id="{{ $customerReturn->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.returns.destroy', $customerReturn->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud" data-customer-return="{{ $customerReturn->id }}">
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
               {{ $customerReturns->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>
//    jQuery('.edit-product').click(function(){
//        let product_id = jQuery(this).attr('data-product-id');
//        alert(product_id);
//        jQuery('.modalCenter').modal('show');
//        console.log(Popup);
//    });

   jQuery('.delete-item').click(function(){
       let customer_return = jQuery(this).attr('data-customer-return');
       if(confirm('هل متأكد من اتمام حذف المرتجع رقم '+ customer_return)){
           jQuery(this).parents('form').submit();
       }
   });
</script>
@endpush