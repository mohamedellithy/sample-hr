<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;"> تعديل القسم </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.departments-expenses.update',$department_expense->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="mb-3 col-md-5">
                            <label class="form-label" for="basic-default-fullname">اسم القسم</label>
                            <input type="text" class="form-control" id="basic-default-fullname"
                                name="department_name"  value="{{  $department_expense->department_name ?: old('department_name') }}" required />
                            @error('department_name')
                                <span class="text-danger w-100 fs-6">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <label class="form-label" for="basic-default-fullname">القسم الرئيسي</label>
                            <select name="parent_id" class="form-control">
                                <option value>بدون قسم رئيسي</option>
                                @foreach($main_departments as $main_department)
                                    <option value="{{ $main_department->id }}" @if($department_expense->parent_id == $main_department->id) selected @endif>{{ $main_department->department_name }}</option>
                                @endforeach
                            </select>
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

<script>
jQuery('#modalCenter').on('submit','form[method=post]',function(e){
    e.preventDefault();
    let password = prompt("من فضلك قم بادخال كلمة المرور: ", "");
    if (password == "Opera@94") {
        jQuery(this).unbind('submit').submit();
    } else {
        alert('كلمة المرور خاطئة ');
    }
});
</script>