<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;">تعديل مبايعه </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="basic-default-fullname">كاش</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="cash" min="0" value="{{  $sale->cash }}" required />
                                @error('cash')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-4">
                                <label class="form-label" for="basic-default-company"> بنك</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="bank" min="0" step=".001" value="{{  $sale->bank }}" required />
                                @error('bank')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> كريدت</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="credit_sales" min="0" step=".001" value="{{  $sale->credit_sales }}" required />
                                @error('credit_sales')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> التاريخ</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="sale_date" value="{{  $sale->sale_date }}" max="{{ date('Y-m-d') }}" required />
                                @error('sale_date')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
            الغاء
        </button>
        <button type="submit" class="btn btn-danger btn-sm">تحديث </button>
    </div>
</form>
