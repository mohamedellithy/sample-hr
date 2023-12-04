<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;"> تعديل سلف موظف </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.employeeAdvances.update', $employeeAdvance->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-10">
                                <label class="form-label" for="basic-default-fullname">الموظف</label>
                                <select name="employee_id" id="largeSelect" class="form-control form-select2" required>
                                <option value=""> الموظف</option>
                                @foreach (  $employees as  $employee)
                                    <option value="{{ $employee->id }}" @isset($employeeAdvance->employee_id) @if ($employeeAdvance->employee_id == $employee->id ) selected @endif @endisset>{{  $employee->name }}</option>
                                @endforeach
                            </select>
                                @error('employee_id')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> المبلغ</label>
                                <input type="number" class="form-control" id="basic-default-fullname"
                                    name="amount" min="0" value="{{  $employeeAdvance->amount }}" required />
                                @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> التاريخ</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="advance_date" value="{{  $employeeAdvance->advance_date }}" max="{{ date('Y-m-d') }}" required />
                                @error('advance_date')
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
