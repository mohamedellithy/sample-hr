@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
$client_filter = request()->query('client_filter') ?: null;

@endphp
@section('content')

<div class="container-fluid">
    <br/>

    <!-- Basic Layout -->
    <form action="{{ route('admin.clientSales.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة مبايعه عميل جديده</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">العميل</label>
                                    <select type="text" name="client_id" class="form-control form-select2 selectProduct" required>
                                    <option value="">اختر عميل</option>
                                    @foreach ($clients as $client)
                                    <option value={{ $client->id }}>{{ $client->name }}</option>
                                    @endforeach
                                    </select>
                                @error('client_id')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company">قيمة الفاتورة</label>
                                <input type="number" class="form-control" id="amount"
                                    name="amount" min="0" step=".001" value="{{ old('amount') }}" required />
                                @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> المدفوع</label>
                                <input type="number" class="form-control" id="paid"
                                    name="paid" min="0" step=".001" value="{{ old('paid') }}" required />
                                @error('paid')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> الغير مدفوع</label>
                                <input type="number" class="form-control" id="remained"
                                    name="remained" min="0" step=".001" value="{{ old('remained') }}" readonly/>
                                @error('remained')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> التاريخ</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="sale_date" value="{{ old('sale_date') ?: date('Y-m-d') }}" required />
                                @error('sale_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">اضافة</button>
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
           <h5 class="card-header">عرض مبيعات العملاء</h5>
           <div class="card-header py-3 ">

               <div class="d-flex justify-content-between" style="background-color: #eee;">
                    <form id="filter-data" method="get" class=" justify-content-between">
                        <div class="d-flex justify-content-between" style="background-color: #eee;">
                            <div class="nav-item d-flex align-items-center m-2">
                                <select name="client_filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control form-select2">
                                    <option value="">فلتر العميل</option>
                                    @foreach (  $clients as  $client)
                                        <option value="{{ $client->id }}" @isset($client_filter) @if ($client_filter == $client->id ) selected @endif @endisset>{{  $client->name }}</option>
                                    @endforeach
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
                    <form  method="post" action="{{ route('admin.clientSales.export') }}">
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
                            <th>قيمة الفواتير</th>
                            <th>المدفوع</th>
                            <th>الغير مدفوع</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($clientSales as $clientSale)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>
                                <td>
                                    {{  $clientSale->name }}
                                </td>
                                <td>
                                    
                                    {{  formate_price($clientSale->total_amount)}}
                                </td>
                                <td>
                                    {{  formate_price($clientSale->total_paid + $clientSale->payments()->sum('amount') )}}
                                </td>
                                <td>
                                    {{  formate_price($clientSale->total_amount - ( $clientSale->total_paid + $clientSale->payments()->sum('amount') ) )}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a  class="crud" href="{{ route('admin.clientSales.show',$clientSale->id) }}">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                   </tbody>
               </table>
           </div>
           <br/><br/>
           <div class="d-flex flex-row justify-content-center">
               {{ $clientSales->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')

<script>

jQuery('.edit-clientSale').click(function(){
        let data_edit = jQuery(this).attr('data-clientSale-id');
        let Popup = jQuery('#modalCenter').modal('show');
        let url = "{{ route('admin.clientSales.edit',':id') }}";
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

    jQuery('#amount,#paid').keyup(function(){
        let amount = jQuery('#amount').val();
        let paid_amount = jQuery('#paid').val();
        jQuery('#remained').val(Number(amount) - Number(paid_amount)); 
    });

</script>
@endpush
