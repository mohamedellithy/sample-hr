<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;">تعديل الموارد </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.money-resources.update', $money_resource->id) }}" method="POST" enctype="multipart/form-data">
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
                                name="value" min="0" step=".001" value="{{  $money_resource->value }}" required />
                            @error('value')
                                <span class="text-danger w-100 fs-6">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-default-fullname">المصدر</label>
                            <select name="type" class="form-control form-select2 selectProduct" required>
                                <option value>اختر المصدر</option>
                                <option value="balance" @if($money_resource->type == 'balance') selected @endif>رصيد سابق</option>
                                <option value="bank_withdraw" @if($money_resource->type == 'bank_withdraw') selected @endif>كاش من البنك</option>
                                <option value="outgoing_resource" @if($money_resource->type == 'outgoing_resource') selected @endif>مصادر خارجية</option>
                                <option value="sales" @if($money_resource->type == 'sales') selected @endif>مبيعات</option>
                            </select>
                            @error('type')
                                <span class="text-danger w-100 fs-6">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <label class="form-label" for="basic-default-fullname">تاريخ المصدر</label>
                            <input type="date" class="form-control" id="basic-default-fullname"
                                name="resource_date" value="{{  $money_resource->resource_date ?: old('resource_date') }}" max="{{ date('Y-m-d') }}" required />
                            @error('resource_date')
                                <span class="text-danger w-100 fs-6">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-default-fullname">البيان</label>
                            <input type="text" class="form-control" id="basic-default-fullname"
                                name="description" value="{{  $money_resource->description ?: old('description') }}" />
                            @error('description')
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
