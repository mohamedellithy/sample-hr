@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
@endphp
@section('content')

<div class="container-fluid">
    <br/>
    <!-- Basic Layout -->
    <form action="{{ route('admin.sales.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-11">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة مبايعه جديده</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="basic-default-fullname">كاش</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="cash" min="0" value="{{ old('cash') }}" required />
                                @error('cash')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-4">
                                <label class="form-label" for="basic-default-company"> كريدت</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="bank" min="0" value="{{ old('bank') }}" required />
                                @error('bank')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                                <div class="mb-3 col-md-4">
                                <label class="form-label" for="basic-default-company"> خصم</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="discount" min="0" value="{{ old('discount') }}" required />
                                @error('discount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> آجل</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="credit_sales" min="0" value="{{ old('credit_sales') }}" required />
                                @error('credit_sales')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> التاريخ</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="sale_date" value="{{ old('sale_date') }}" required />
                                @error('sale_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">اضافة مبايعه</button>
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
           <h5 class="card-header">عرض المصروفات</h5>
           <div class="card-header py-3 ">

               <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">

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
                <form  method="post" action="{{ route('admin.sales.export') }}">
                  @csrf
                            <div class="nav-item d-flex align-items-center m-2">
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
                            <th>كاش</th>
                            <th>كريدت</th>
                            <th>خصم</th>
                            <th>آجل</th>
                            <th>المجموع</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($sales as $sale)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>

                                <td>
                                {{  formate_price($sale->cash) }}
                                </td>
                                <td>
                                    {{  formate_price($sale->bank )}}
                                </td>
                                <td>
                                    {{  formate_price($sale->discount) }}
                                </td>
                                <td>
                                    {{  formate_price($sale->credit_sales) }}
                                </td>
                                <td>
                                    {{  formate_price($sale->cash + $sale->bank + $sale->discount + $sale->credit_sales)}}
                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $sale->sale_date }}
                                     </span>
                                </td>

                                <td>
                                    <div class="d-flex">

                                        <a  class="crud edit-sale" data-sale-id="{{ $sale->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.sales.destroy', $sale->id) }}">
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
               {{ $sales->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')
<script>

jQuery('.edit-sale').click(function(){
        let data_edit = jQuery(this).attr('data-sale-id');
        let Popup = jQuery('#modalCenter').modal('show');
        let url = "{{ route('admin.sales.edit',':id') }}";
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
