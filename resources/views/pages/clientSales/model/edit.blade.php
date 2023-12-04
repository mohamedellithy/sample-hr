<h4 class="fw-bold py-3" style="padding-bottom: 0rem !important;"> تعديل مبايعه عميل </h4>
<!-- Basic Layout -->
<form action="{{ route('admin.clientSales.update', $clientSale->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">الموظف</label>
                                <select name="client_id" id="largeSelect" class="form-control form-select2" required>
                                <option value=""> الموظف</option>
                                @foreach (  $clients as  $client)
                                    <option value="{{ $client->id }}" @isset($clientSale->client) @if ($clientSale->client_id == $client->id ) selected @endif @endisset>{{  $client->name }}</option>
                                @endforeach
                            </select>
                                @error('client_id')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> المبلغ</label>
                                <input type="number" class="form-control" id="amount"
                                    name="amount" min="0" step=".001" value="{{  $clientSale->amount }}" required />
                                @error('amount')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-2">
                          <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-company"> التاريخ</label>
                                <input type="date" class="form-control" id="basic-default-fullname"
                                    name="sale_date" value="{{  $clientSale->sale_date }}" max="{{ date('Y-m-d') }}" required />
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
<script>
    jQuery('#amount,#paid').keyup(function(){
        let amount = jQuery('#amount').val();
        let paid_amount = jQuery('#paid').val();
        jQuery('#remained').val(Number(amount) - Number(paid_amount)); 
    });
</script>