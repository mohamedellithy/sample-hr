@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
$client_filter = request()->query('client_filter') ?: null;

$total_bill = $ClientSale->sum('amount');
$total_paid = $ClientSale->sum('paid');
$total_payment = $client->payments()->sum('amount');

@endphp
@section('content')

<div class="container-fluid">
    <br/>

    <!-- Basic Layout -->
    <form action="{{ route('admin.client-payemnts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">تنزيل دفع من حساب العميل</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company">قيمة الدفعة</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="amount" max="{{ $total_bill - ( $total_paid + $total_payment ) }}" step=".001" value="{{ old('amount') }}" required />
                                @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                                <input type="hidden" name="client_id" value="{{ $client->id }}" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">تنزيل</button>
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
            <h5 class="card-header">كشف حساب العميل ( {{ $client->name }} ) </h5>
           <div class="card-header">
                <ul style="display: flex;flex-wrap: wrap;justify-content: flex-start;;align-items: center;">
                    <li style="list-style: none;background-color: #e0f2f1;padding: 17px;margin-left: 14px;">
                        <p>اجمالى الفواتير</p>
                        <strong>{{ formate_price($total_bill) }}</strong>
                    </li>
                    <li style="list-style: none;background-color: #e8eaf6;padding: 17px;margin-left: 14px">
                        <p>اجمالى المدفوعات</p>
                        <strong>{{ formate_price($total_paid + $total_payment) }}</strong>
                    </li>
                    <li style="list-style: none;background-color: #fbe9e7;padding: 17px;margin-left: 14px">
                        <p>اجمالى الغير مدفوع</p>
                        <strong>{{ formate_price($total_bill - ( $total_paid +  $total_payment ) ) }}</strong>
                    </li>
                    <li style="list-style: none;background-color:#eceff1;padding: 17px;margin-left: 14px">
                        <p>عدد الفواتير</p>
                        <strong>{{ $ClientSale->count() }}</strong>
                    </li>
                </ul>
           </div>
            <h5 class="card-header">
                عرض مبيعات ( {{ $client->name }} ) 
                <br/>
                <br/>
                <a href="{{ route('admin.clientSales.show',$client->id) }}" class="btn btn-danger btn-sm">
                    عرض الفواتير
                </a>
                <a href="{{ route('admin.client-payments.get',['client_id' => $client->id]) }}" class="btn btn-dark btn-sm">
                    عرض المدفوعات
                </a>
            </h5>
            <div class="card-header py-3 ">
               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">

                        <div class="nav-item d-flex align-items-center m-2">
                            <input type="text" onchange="document.getElementById('filter-data').submit()" class=" form-control" placeholder=" من - الي" @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>
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
                    <form  method="post" action="{{ route('admin.clientPayments.export') }}">
                            @csrf
                            <div class="nav-item d-flex align-items-center m-2">
                            <input type="hidden" name="client_filter" value="{{ $client_filter }}">
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
                           <th>#</th>
                            <th>العميل</th>
                            <th>قيمة المدفوع</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($clientPayments as $clientPayment)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>
                                <td>
                                    @if ($clientPayment->client)
                                    {{  $clientPayment->client->name }}
                                    @endif
                                </td>
                                <td>
                                    {{  formate_price($clientPayment->amount )}}
                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $clientPayment->created_at }}
                                     </span>
                                </td>
                                <td>
                                    <div class="d-flex">

                                        <a  class="crud edit-clientPayment" data-clientPayment-id="{{ $clientPayment->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.clientPayment.destroy', ['payment_id' => $clientPayment->id]) }}">
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
               {{ $clientPayments->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')

<script>

jQuery('.edit-clientPayment').click(function(){
        let data_edit = jQuery(this).attr('data-clientPayment-id');
        let Popup = jQuery('#modalCenter').modal('show');
        let url = "{{ route('admin.clientPayment.edit',':payment_id') }}";
        url = url.replace(':payment_id',data_edit);
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
