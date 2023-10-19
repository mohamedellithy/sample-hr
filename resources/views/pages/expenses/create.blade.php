@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">اضافة مصروف جديد</h4>
        <!-- Basic Layout -->
        @if (flash()->message)
            <div class="{{ flash()->class }}">
                {{ flash()->message }}
            </div>

            @if (flash()->level === 'error')
                This was an error.
            @endif
        @endif
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">نوع المصروف </label>
                            <select name="service" id="largeSelect" class="form-control" required>
                                <option value="">نوع الخدمه</option>
                                <option value="بار">بار</option>
                                <option value="شيشه">شيشه</option>
                                <option value="صيانه">صيانه</option>
                                <option value="مطبخ">مطبخ</option>
                                <option value="owner">owner</option>
                            </select>
                                @error('service')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">المبلغ</label>
                                <input type="number" value="{{ old('amount') }}" class="form-control" placeholder="ادخل  مبلغ المصروف"  name="amount" required/>
                                @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-3">

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الصرف</label>
                                <input type="date" value="{{ old('expense_date') }}" class="form-control" placeholder="ادخل  تاريخ الصرف" name="expense_date" required/>
                                @error('expense_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">المرفق</label>
                                <input type="file"  value="{{ old('attachment') }}" class="form-control" placeholder="اختر مرفق" name="attachment" accept="image/gif, image/jpeg, image/png"/>
                                @error('attachment')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                         <div class="row mt-4 text-center">
                            <div class="col">
                            <button type="submit" class="btn btn-primary">اضافة مصروف</button>

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
