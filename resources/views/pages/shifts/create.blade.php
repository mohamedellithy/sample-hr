@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;"> اضافة شيفت </h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('admin.shifts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5> اضافة  يدوي </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company"> اسم الموظف</label>
                                    <select name="employee_id" id="largeSelect" class="form-control form-select2">
                                        <option value=""> الموظف</option>
                                        @foreach (  $employees as  $employee)
                                        <option value="{{ $employee->id }}">{{  $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">تاريخ </label>
                                    <input type="date" value="{{ old('date') }}" class="form-control" placeholder="ادخل  تاريخ " name="date" required/>
                                    @error('date')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">

                                <div class="col">
                                    <label class="form-label" for="basic-default-company">الحضور</label>
                                    <input type="time" min="0" value="{{ old('clock_in') }}" class="form-control" name="clock_in" required/>
                                    @error('clock_in')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="form-label" for="basic-default-company">الانصراف</label>
                                    <input type="time" min="0" value="{{ old('clock_out') }}" class="form-control" name="clock_out" required/>
                                    @error('clock_out')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4 text-center">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">اضافة</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="{{ route('admin.shifts.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-header">
                            <h5> اضافة  ملف </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-8">
                                    <label class="form-label" for="basic-default-company"> اختر ملف (csv)</label>
                                    <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" min="0" value="{{ old('file') }}" class="form-control" name="file" required/>
                                    @error('file')
                                            <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <button type="submit" style="margin-top:29px !important;" class=" btn btn-primary">اضافة</button>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </form>
                <div class="card mb-5">
                    <div class="card-header">
                        <h5> عرض الشيفتات </h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                 <tr class="table-dark">
                                    <th>#</th>
                                     <th>الموظف</th>
                                     <th>التاريخ</th>

                                     <th>الحضور</th>
                                     <th>الانصراف</th>
                                     <th></th>
                                 </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @if(session()->has('new_shifts'))
                                    @foreach(session()->get('new_shifts') as $shift)
                                        <tr>
                                            <td>
                                                {{$loop->index + 1 }}
                                            </td>

                                            <td>
                                            {{  $shift->employee->name }}
                                            </td>
                                            <td>
                                                {{  $shift->date }}
                                            </td>

                                            <td>
                                                {{ formate_time2($shift->clock_in) }}
                                            </td>
                                            <td>
                                                {{  formate_time2($shift->clock_out )}}
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    jQuery('.delete-item').click(function(){
        let employee_name = jQuery(this).attr('data-employee-name');
        if(confirm('هل متأكد من اتمام حذف  '+ employee_name)){
           jQuery(this).parents('form').submit();
        }
    });
</script>
@endpush


@push('style')
<style>

</style>
@endpush
