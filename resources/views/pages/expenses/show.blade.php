@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold py-3  mb-3" style="padding-bottom: 0rem !important;">عرض مصروف </h4>
        <!-- Basic Layout -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">

                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">نوع المصروف </label>
                                <input type="text" value="{{$expense->section }}" class="form-control" readOnly/>
                            </div>

                            <div class="col">
                                <label class="form-label" for="basic-default-company">البند</label>
                                <input type="text" value="{{$expense->sub_service }}" class="form-control" readOnly/>
                            </div>
                            
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">رقم الفاتورة</label>
                                <input type="text" value="{{$expense->bill_no }}" class="form-control"  readOnly/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="basic-default-company">قيمة الفاتورة</label>
                                <input type="text" value="{{$expense->amount }}" class="form-control"  readOnly/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المدفوع</label>
                                <input type="number" value="{{$expense->paid_amount }}" class="form-control" placeholder="ادخل  مبلغ الاجل"  name="pending_amount" readOnly/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المتبقي</label>
                                <input type="number" value="{{$expense->pending_amount }}" class="form-control" placeholder="ادخل  مبلغ الاجل"  name="pending_amount" readOnly/>
                            </div>
                            
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label" for="basic-default-company">المصروف تابع ل</label>
                                <input type="text" value="{{$expense->supplier }}" class="form-control" placeholder="الاسم التابع له المصروف" name="supplier" readOnly/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="basic-default-company">البيان</label>
                                <input type="text" value="{{$expense->expense_description }}" class="form-control" placeholder="البيان" name="supplier" readOnly/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="basic-default-company">تاريخ الصرف</label>
                                <input type="date" value="{{ $expense->expense_date }}" class="form-control"  readOnly/>

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
