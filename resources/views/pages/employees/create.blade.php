@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;"> اضافة موظف جديدة</h4>
        <!-- Basic Layout -->
 
        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company"> اسم الموظف</label>
                                <input type="text" value="{{ old('name') }}" class="form-control" placeholder="ادخل اسم الموظف"
                                    name="name" required/>
                                @error('name')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">الجنسيه</label>
                                <input type="text" value="{{ old('nationality') }}" class="form-control" placeholder="ادخل جنسيه الموظف"  name="nationality" required/>
                                @error('nationality')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المرتب</label>
                                <input type="number" min="0" value="{{ old('salary') }}" class="form-control" placeholder="ادخل مرتب الموظف"name="salary" required/>
                                @error('salary')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">الساعه</label>
                                <input type="number" min="0" value="{{ old('hour') }}" class="form-control" placeholder="ادخل ساعه الموظف" name="hour" required/>
                                @error('hour')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">رقم الباسبور</label>
                                <input type="number" min="0" value="{{ old('passport_no') }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_no" required/>
                                @error('passport_no')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الميلاد</label>
                                <input type="date" value="{{ old('birthday') }}" class="form-control" placeholder="ادخل  تاريخ الميلاد" name="birthday" required/>
                                @error('birthday')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ انتهاء الباسبور</label>
                                <input type="date" value="{{ old('passport_expiry') }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_expiry" required/>
                                @error('passport_expiry')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ انتهاء البطاقه</label>
                                <input type="date"  value="{{ old('card_expiry') }}" class="form-control" placeholder="" name="card_expiry" required/>
                                @error('card_expiry')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الانضمام</label>
                                <input type="date"  value="{{ old('join_date') }}" class="form-control" placeholder="" name="join_date" required/>
                                @error('join_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                         <div class="row mt-4 text-center">
                            <div class="col">
                            <button type="submit" class="btn btn-primary">اضافة موظف</button>

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
