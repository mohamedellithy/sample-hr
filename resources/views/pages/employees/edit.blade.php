@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">تعديل موظف</h4>
        <!-- Basic Layout -->


            <form action="{{ route('admin.employees.update',$employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company"> اسم الموظف</label>
                                <input type="text" value="{{ $employee->name }}" class="form-control" placeholder="ادخل اسم الموظف"
                                    name="name" required/>
                                @error('name')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror

                            </div>


                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المرتب</label>
                                <input type="number" min="0" value="{{ $employee->salary }}" class="form-control" placeholder="ادخل مرتب الموظف"name="salary" required/>
                                @error('salary')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">الجنسيه</label>
                                <input type="text" value="{{ $employee->nationality }}" class="form-control" placeholder="ادخل جنسيه الموظف"  name="nationality" required/>
                                @error('nationality')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">رقم الباسبور</label>
                                <input type="number" min="0" value="{{ $employee->passport_no }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_no" required/>
                                @error('passport_no')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الميلاد</label>
                                <input type="date" value="{{ $employee->birthday }}" class="form-control" placeholder="ادخل  تاريخ الميلاد" name="birthday" required/>
                                @error('birthday')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">رقم المدني</label>
                                <input type="text" value="{{ $employee->citizen_no ?: old('citizen_no') }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="citizen_no" required/>
                                @error('citizen_no')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ انتهاء الرقم المدنى</label>
                                <input type="date" value="{{ $employee->citizen_expiry ?: old('citizen_expiry') }}" class="form-control" placeholder="ادخل  تاريخ الميلاد" name="citizen_expiry" required/>
                                @error('citizen_expiry')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ انتهاء الباسبور</label>
                                <input type="date" value="{{ $employee->passport_expiry }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_expiry" required/>
                                @error('passport_expiry')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الانضمام</label>
                                <input type="date"  value="{{ $employee->join_date }}" class="form-control" placeholder="" name="join_date" required/>
                                @error('join_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                         <div class="row mt-4 text-center">
                            <div class="col">
                            <button type="submit" class="btn btn-danger">تعديل موظف</button>

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
