<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;"> تعديل مبايعه عميل </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.clientPayment.update', $clientPayment->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> المبلغ</label>
                                <input type="number" class="form-control" id="amount"
                                    name="amount" min="0" step=".001" value="{{  $clientPayment->amount }}" required />
                                @error('amount')
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