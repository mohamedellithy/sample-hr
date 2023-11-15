@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">اضافة مصروف جديد</h4>
        <!-- Basic Layout -->
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">القسم</label>
                                    <select name="section" id="SelectSection" class="form-control" required>
                                        <option value="مصروفات المباشرة" sect="1">مصروفات المباشرة</option>
                                        <option value="صيانة"  sect="2">صيانة</option>
                                        <option value="كهرباء" sect="3">كهرباء</option>
                                        <option value="مياه"   sect="3">مياه</option>
                                        <option value="انترنت" sect="3">انترنت</option>
                                    </select>
                                    @error('section')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="form-label" for="basic-default-company"> البند </label>
                                    <select name="sub_service" id="Selectsub" class="form-control" required>
                                        <option value="مطبخ"    sect="1">مطبخ</option>
                                        <option value="شيشة"    sect="1">شيشة</option>
                                        <option value="بار"     sect="1">بار</option>
                                        <option value="اونر"   sect="2">اونر</option>
                                        <option value="ميديكال" sect="2">ميديكال</option>
                                        <option value="مطعم"    sect="3">مطعم</option>
                                        <option value="سكن العمال" sect="3">سكن العمال</option>
                                    </select>
                                    @error('sub_service')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">رقم الفاتورة</label>
                                    <input type="text" value="{{ old('bill_no') }}" class="form-control" placeholder="ادخل  مبلغ المصروف"  name="bill_no" required/>
                                    @error('bill_no')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="form-label" for="basic-default-company">المصروف تابع ل أو المورد</label>
                                    <input type="text" value="{{ old('supplier') }}" class="form-control" placeholder="المصروف تابع ل أو المورد" name="supplier" required/>
                                    @error('supplier')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">قيمة الفاتورة</label>
                                    <input type="number" step=".001" value="{{ old('amount') }}" class="form-control" placeholder="ادخل  مبلغ المصروف"  name="amount" required/>
                                    @error('amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">المدفوع</label>
                                    <input type="number" step=".001" value="{{ old('paid_amount') }}" class="form-control" placeholder="ادخل المبلغ المدفوع"  name="paid_amount" required/>
                                    @error('paid_amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">الاجل</label>
                                    <input type="number" step=".001" value="{{ old('pending_amount') }}" class="form-control" placeholder="ادخل  مبلغ الاجل"  name="pending_amount" required/>
                                    @error('pending_amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">البيان</label>
                                    <input type="text" value="{{ old('expense_description') }}" class="form-control" placeholder="ادخل  تاريخ الصرف" name="expense_description" required/>
                                    @error('expense_description')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">تاريخ الصرف</label>
                                    <input type="date" value="{{ old('expense_date') }}" class="form-control" placeholder="ادخل  تاريخ الصرف" name="expense_date" required/>
                                    @error('expense_date')
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
      jQuery('#SelectSection').change(function(){
           let sect = jQuery(this).find('option:selected').attr('sect');
           jQuery('#Selectsub').find(`option`).attr('selected',false);
           jQuery('#Selectsub').find(`option`).hide();
           jQuery('#Selectsub').find(`option[sect="${sect}"]`).show();
           jQuery('#Selectsub').find(`option[sect="${sect}"]`).eq(0).attr('selected',true);
      });
    </script>
@endpush


@push('style')
<style>

</style>
@endpush
