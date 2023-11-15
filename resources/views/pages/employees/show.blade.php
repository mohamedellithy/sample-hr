@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">عرض موظف</h4>
        <!-- Basic Layout -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company"> اسم الموظف</label>
                                <input type="text" value="{{ $employee->name }}" class="form-control" placeholder="ادخل اسم الموظف"
                                    name="name" readOnly/>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المرتب</label>
                                <input type="number" min="0" step=".001" value="{{ $employee->salary }}" class="form-control" placeholder="ادخل مرتب الموظف"name="salary" readOnly/>

                            </div>


                            <div class="col">
                                <label class="form-label" for="basic-default-company">الجنسيه</label>
                                <input type="text" value="{{ $employee->nationality }}" class="form-control" placeholder="ادخل جنسيه الموظف"  name="nationality" readOnly/>

                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">رقم الباسبور</label>
                                <input type="text" min="0" value="{{ $employee->passport_no }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_no" readOnly/>

                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الميلاد</label>
                                <input type="date" value="{{ $employee->birthday }}" class="form-control" placeholder="ادخل  تاريخ الميلاد" name="birthday" readOnly/>

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ انتهاء الباسبور</label>
                                <input type="date" value="{{ $employee->passport_expiry }}" class="form-control" placeholder="ادخل  رقم الباسبور"name="passport_expiry" readOnly/>

                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الانضمام</label>
                                <input type="date"  value="{{ $employee->join_date }}" class="form-control" placeholder="" name="join_date" readOnly/>

                            </div>
                        </div>





                        </div>
                    </div>
                </div>

            </div>

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
