@php 
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
$datefilter     = request()->query('datefilter') ?: null;

if($datefilter):
    $filter_date    = explode('-',request('datefilter'));
    $from           = Carbon::parse(trim($filter_date[0]))->format('Y-m-d');
    $to             = Carbon::parse(trim($filter_date[1]))->format('Y-m-d');
endif;
@endphp
@extends('layouts.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">مرحيا {{ auth()->user()->name }} 🎉</h5>
                                    <form method="get" action="">
                                        <div class="form-group">
                                            <label>تحديد الشهر للاحصائيات</label>
                                            <input style="border:1px solid #AD1457 !important" type="text" onchange="document.getElementById('filter-data').submit()" class=" form-control" placeholder="من - الي  " @isset($datefilter) value="{{ $datefilter }}" @endisset id="datefilter" name="datefilter"/>
                                            <br/>
                                            <button class="btn btn-info btn-sm">تصدير</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-5 text-center text-sm-left">
                                <div class="card-body pb-0 px-0 px-md-4">
                                    <img src="/theme_2/assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/avatars/user_avatar.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                الموظفين
                            </span>
                            <h3 class="card-title mb-2">
                                {{ \App\Models\Employee::count() }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/chart-success.png" alt="chart danger" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                المصروفات
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                {{ \App\Models\Expense::whereBetween(
                                    'expense_date',[
                                        $from,
                                        $to
                                    ])->sum('amount') }}
                                @else
                                {{ \App\Models\Expense::sum('amount') }}
                                @endif
                            </h3>
                            <small class="text-success fw-semibold">
                                <i class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/chart.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                المبيعات
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{  \App\Models\Sale::whereBetween('sale_date',[
                                            $from,
                                            $to
                                        ])->sum(DB::raw('cash + bank + credit_sales')) }}
                                @else
                                    {{ \App\Models\Sale::sum(DB::raw('cash + bank + credit_sales')) }}
                                @endif

                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/avatars/user_avatar.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                العملاء
                            </span>
                            <h3 class="card-title mb-2">
                                {{ \App\Models\Client::count() }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/paypal.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                الموارد
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\MoneyResource::whereBetween('resource_date',[
                                        $from,
                                        $to
                                    ])->sum('value') }}
                                @else
                                    {{ \App\Models\MoneyResource::sum('value') }}
                                @endif
                            </h3>
                            
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/paypal.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                مبيعات الموظفين
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\EmployeeSale::whereBetween('sale_date',[
                                        $from,
                                        $to
                                    ])->sum('remained') }}
                                @else
                                    {{ \App\Models\EmployeeSale::sum('remained') }}
                                @endif
                                
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                سلف الموظفين
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\EmployeeAdvance::whereBetween('advance_date',[
                                        $from,
                                        $to
                                    ])->sum('amount') }}
                                @else
                                    {{ \App\Models\EmployeeAdvance::sum('amount') }}
                                @endif
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                مدفوعات الموظفين
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\EmployeePaid::whereBetween('month',[
                                        $from,
                                        $to
                                    ])->sum('paid') }}
                                @else
                                    {{ \App\Models\EmployeePaid::sum('paid') }}
                                @endif

                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                مبيعات الموظفين المسددة
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\EmployeeSale::where('status','paid')->whereBetween('sale_date',[
                                        $from,
                                        $to
                                    ])->sum('remained') }}
                                @else
                                    {{ \App\Models\EmployeeSale::where('status','paid')->sum('remained') }}
                                @endif
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="/theme_2/assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">
                                مبيعات الموظفين الغير مسددة
                            </span>
                            <h3 class="card-title mb-2">
                                @if($datefilter)
                                    {{ \App\Models\EmployeeSale::where('status','unpaid')->whereBetween('sale_date',[
                                        $from,
                                        $to
                                    ])->sum('remained') }}
                                @else
                                    {{ \App\Models\EmployeeSale::where('status','unpaid')->sum('remained') }}
                                @endif
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> ريال
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Revenue -->
    </div>
</div>
@endsection
