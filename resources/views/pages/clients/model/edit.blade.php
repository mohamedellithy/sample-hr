<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;">تعديل عميل </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-default-fullname">الاسم</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    name="name" min="0" value="{{  $client->name }}" required />
                                @error('name')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-default-company"> الهاتف</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    name="phone" min="0" value="{{  $client->phone }}" />
                                @error('phone')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror

                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
            الغاء
        </button>
        <button type="submit" class="btn btn-danger btn-sm">تحديث عميل</button>
    </div>
</form>
