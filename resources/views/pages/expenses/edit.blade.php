@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">عرض مصروف </h4>
        <!-- Basic Layout -->



            <form action="{{ route('admin.expenses.update',$expense->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                            <label class="form-label" for="basic-default-company">نوع المصروف </label>
                                <select name="service" id="largeSelect" class="form-control">
                                <option value="">فلتر الخدمه</option>
                                <option value="بار" @isset($expense->service) @if ($expense->service=='بار' ) selected @endif @endisset>بار</option>
                                <option value="شيشه" @isset($expense->service) @if ($expense->service=='شيشه' ) selected @endif @endisset>شيشه</option>
                                <option value="صيانه" @isset($expense->service) @if ($expense->service=='صيانه' ) selected @endif @endisset>صيانه</option>
                                <option value="مطبخ" @isset($expense->service) @if ($expense->service=='مطبخ' ) selected @endif @endisset>مطبخ</option>
                                <option value="owner" @isset($expense->service) @if ($expense->service=='owner' ) selected @endif @endisset>owner</option>
                            </select>
                              @error('service')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">المبلغ</label>
                                <input type="number" name="amount" value="{{$expense->amount }}" class="form-control" required/>
                              @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-3">

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الصرف</label>
                                <input type="date" name="expense_date" value="{{ $expense->expense_date }}" class="form-control"  required/>
                                @error('expense_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>

                             <div class="row mt-4 text-center">
                            <div class="col">
                            <button type="submit" class="btn btn-danger">تعديل مصروف</button>

                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
@push('script')
    <script type="text/javascript">

    </script>
@endpush


@push('style')
<style>

</style>
@endpush
