@php 
$datefilter = request()->query('datefilter') ?: null;
@endphp
@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <br/>
    <!-- Basic Layout -->
    <form method="get" action="">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">القيود اليومية</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company">تحديد الشهر</label>
                                <input type="text" class=" form-control" placeholder="من - الي  " @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">عرض القيود</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
        <form  method="post" action="{{ route('admin.daily-deals.pdf') }}">
            @csrf
            <input type="text" class=" form-control" placeholder="من - الي  " @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter" hidden/>
            <div class="nav-item d-flex align-items-center m-2">
                <button type="submit" class="btn btn-primary btn-sm">تصدير</button>
            </div>
        </form>
        <h5 class="card-header" style="text-align:center;color:red">
            القيود اليومية
            <br/><br/>
            {{ $date_range}}
        </h5>
       <div class="card">
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                            <th>التاريخ</th>
                            <th>البيان</th>
                            <th>الوصف</th>
                            <th>المدين</th>
                            <th>الدائن</th>
                            <th>الرصيد</th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @php $balance = 0; @endphp
                        @foreach($data as $item)
                            @if(isset($item->item_expense_id))
                                @php $balance -=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        {{ isset($item->item_name) ? $item->item_name : '-' }}
                                    </td>
                                    <td>
                                        {{ isset($item->item_description) ? $item->item_description : '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_expenses_payments_id))
                                @php $balance +=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        مدفوعات للمصروفات
                                    </td>
                                    <td>
                                        تسديد دفع من المصروفات
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_money_resources_id))
                                @php $balance +=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        الخزنة
                                    </td>
                                    <td>
                                        {{ get_resource_name($item->item_name) }}
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_sales_id))
                                @php $balance +=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        مبيعات
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_sales_payments_id))
                                @php $balance +=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        تحصيل مدفوعات المبيعات 
                                    </td>
                                    <td>
                                        {{ $item->item_description ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_employee_advances_id))
                                @php $balance -=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        سلفه لموظف
                                    </td>
                                    <td>
                                        {{ $item->item_description ?: '-' }}
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @elseif(isset($item->item_employee_paids_id))
                                @php $balance -=$item->item_amount @endphp
                                <tr>
                                    <td>
                                        {{ $item->item_created_at }}
                                    </td>
                                    <td>
                                        تسديد رواتب الموظف
                                    </td>
                                    <td>
                                        {{ $item->item_description ?: '-' }}
                                        عن شهر
                                        {{ $item->item_month ?: '-' }}
                                    </td>
                                    <td>
                                        {{ $item->item_amount ?: '-' }}
                                    </td>
                                    <td>
                                        {{  '-' }}
                                    </td>
                                    <td>
                                        {{  round($balance,3) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                   </tbody>
               </table>
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')

@endpush
