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
                                    <label class="form-label" for="basic-default-company">القسم</label>
                                    <select name="section" id="SelectSection" class="form-control" required>
                                        <option value>تحديد القسم الرئيسي</option>
                                        @foreach($main_departments as $main_department)
                                            <option value="{{ $main_department->id }}">{{ $main_department->department_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="form-label" for="basic-default-company"> البند </label>
                                    <select name="sub_service" id="Selectsub" class="form-control" required>
                                    </select>
                                    @error('sub_service')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">رقم الفاتورة</label>
                                    <input type="text" value="{{ $expense->bill_no ?: old('bill_no') }}" class="form-control" placeholder="ادخل  مبلغ المصروف"  name="bill_no" required/>
                                    @error('bill_no')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="form-label" for="basic-default-company">المصروف تابع ل أو المورد</label>
                                    <input type="text" value="{{ $expense->supplier ?: old('supplier') }}" class="form-control" placeholder="المصروف تابع ل أو المورد" name="supplier" required/>
                                    @error('supplier')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">قيمة الفاتورة</label>
                                    <input type="number" id="amount" step=".001" value="{{ $expense->amount ?: old('amount') }}" class="form-control" placeholder="ادخل  مبلغ المصروف"  name="amount" required/>
                                    @error('amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">المدفوع</label>
                                    <input type="number" id="paid_amount" step=".001" value="{{ $expense->payments_sum_value > 0 ? $expense->payments_sum_value : old('paid_amount') }}" class="form-control" placeholder="ادخل المبلغ المدفوع"  name="paid_amount" required/>
                                    @error('paid_amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">الاجل</label>
                                    <input type="number" id="pending_amount" step=".001" value="{{ $expense->payments_sum_value > 0 ? ($expense->amount - $expense->payments_sum_value) : old('pending_amount') }}" class="form-control" placeholder="ادخل  مبلغ الاجل"  name="pending_amount" readonly/>
                                    @error('pending_amount')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">البيان</label>
                                    <input type="text" value="{{ $expense->expense_description ?: old('expense_description') }}" class="form-control" placeholder="ادخل  تاريخ الصرف" name="expense_description" required/>
                                    @error('expense_description')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label" for="basic-default-company">تاريخ الصرف</label>
                                    <input type="date" value="{{ $expense->expense_date ?: old('expense_date') }}" class="form-control" placeholder="ادخل  تاريخ الصرف" name="expense_date" required/>
                                    @error('expense_date')
                                        <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-4 text-center">
                                <div class="col">
                                    <button type="submit" class="btn btn-danger">تعديل المصروف</button>
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
        let sect = jQuery('#SelectSection').find('option:selected').attr('sect');
        jQuery('#Selectsub').find(`option`).hide();
        jQuery('#Selectsub').find(`option[sect="${sect}"]`).show();

        jQuery('#SelectSection').change(function(){
            let parent_id = jQuery(this).val();
            let url = "{{ route('admin.sub-departments',':parent_id') }}";
            url = url.replace(':parent_id',parent_id);
            jQuery('#Selectsub').html("");
            $.ajax({
                url:url,
                type:"GET",
                success: function(data){
                    let option = "";
                    data.sub_departments.forEach(function(item){
                        option +=`<option value="${item.id}">${item.department_name}</option>`;
                    });
                    jQuery('#Selectsub').html(option);
                }
            })
        });

        jQuery('#amount,#paid_amount').keyup(function(){
           let amount = jQuery('#amount').val();
           let paid_amount = jQuery('#paid_amount').val();
           jQuery('#pending_amount').val(Number(amount) - Number(paid_amount)); 
        });
    </script>
@endpush


@push('style')
<style>

</style>
@endpush
