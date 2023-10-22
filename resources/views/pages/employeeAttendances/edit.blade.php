@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;"> تعديل حضور وانصراف </h4>
        <!-- Basic Layout -->

       <form action="{{ route('admin.employeeAttendances.update',$employeeAttendance->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">

                        <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company"> اسم الموظف</label>
                                <select name="employee_id" id="largeSelect" class="form-control form-select2">
                                <option value=""> الموظف</option>
                                @foreach (  $employees as  $employee)
                                <option value="{{ $employee->id }}"
                                @isset($employeeAttendance->employee_id) @if ($employeeAttendance->employee_id == $employee->id ) selected @endif
                                @endisset>{{  $employee->name }}</option>
                                @endforeach
                            </select>
                                @error('employee_id')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ </label>
                                <input type="date" value="{{ $employeeAttendance->attendance_date }}" class="form-control" placeholder="ادخل  تاريخ " name="attendance_date" required/>
                                @error('attendance_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">الحضور</label>
                                <input type="time" min="0" value="{{$employeeAttendance->clock_in  }}" class="form-control" name="clock_in" required/>
                                @error('clock_in')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">الانصراف</label>
                                <input type="time" min="0" value="{{$employeeAttendance->clock_out }}" class="form-control" name="clock_out" required/>
                                @error('clock_out')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                         <div class="row mt-4 text-center">
                            <div class="col">
                            <button type="submit" class="btn btn-danger">تعديل</button>

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
