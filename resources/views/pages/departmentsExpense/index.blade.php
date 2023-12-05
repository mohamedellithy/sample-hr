@extends('layouts.master')
@php

$rows = request()->query('rows') ?: 10;
$filter = request()->query('filter') ?: null;
$datefilter = request()->query('datefilter') ?: null;
$client_filter = request()->query('client_filter') ?: null;
$search = request()->query('search') ?: null;

@endphp
@section('content')

<div class="container-fluid">
    <br/>
    <!-- Basic Layout -->
    <form action="{{ route('admin.departments-expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">اضافة قسم جديده</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">اسم القسم</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    name="department_name"  value="{{ old('department_name') }}" required />
                                @error('department_name')
                                    <span class="text-danger w-100 fs-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="basic-default-fullname">القسم الرئيسي</label>
                                <select name="parent_id" class="form-control">
                                    <option value>بدون قسم رئيسي</option>
                                    @foreach($main_departments as $main_department)
                                        <option value="{{ $main_department->id }}">{{ $main_department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">اضافة</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid">
   <!-- DataTales Example -->
   <div class="card mb-4">
       <div class="card">
           <h5 class="card-header">عرض العملاء</h5>
           <div class="card-header py-3 ">
                <form id="filter-data" method="get" class=" justify-content-between">
                    <div class="d-flex justify-content-between" style="background-color: #eee;">

                        <div class="nav-item d-flex align-items-center m-2" style="background-color: #fff;padding: 2px;">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input type="text" class="search form-control border-0 shadow-none" onchange="document.getElementById('filter-data').submit()" placeholder="البحث ...." @isset($search) value="{{ $search }}" @endisset id="search" name="search" style="background-color:#fff;"/>
                        </div>

                        <div class="nav-item d-flex align-items-center m-2">
                            <select name="filter" id="largeSelect" onchange="document.getElementById('filter-data').submit()" class="form-control">
                                <option value="">فلتر الاقسام</option>
                                <option value="sort_asc" @isset($filter) @if ($filter=='sort_asc' ) selected @endif @endisset>الاقدم</option>
                                <option value="sort_desc" @isset($filter) @if ($filter=='sort_desc' ) selected @endif @endisset>الاحدث </option>
                            </select>
                        </div>
                        <div class="nav-item d-flex align-items-center m-2">
                            <label style="padding: 0px 5px;color: #636481;">المعروض</label>
                            <select name="rows" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-sm">
                                    <option >10</option>
                                    <option value="50" @isset($rows) @if ($rows=='50' ) selected @endif @endisset>50</option>
                                    <option value="100" @isset($rows) @if ($rows=='100' ) selected @endif @endisset> 100</option>
                            </select>
                        </div>
                    </div>
                </form>
           </div>
           <div class="table-responsive text-nowrap">
               <table class="table">
                   <thead class="table-light">
                        <tr class="table-dark">
                            <th>#</th>
                            <th>الاسم</th>
                            <th>قسم الرئيسي</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                   </thead>
                   <tbody class="table-border-bottom-0">
                        @foreach ($other_departments as $department)
                            <tr>
                                <td>
                                   {{$loop->index + 1 }}
                                </td>

                                <td>
                                {{  $department->department_name }}
                                </td>

                                <td>
                                    {{  $department->department_parent ? $department->department_parent->department_name : ' - ' }}
                                </td>
                                <td>
                                     <span class="badge bg-label-primary me-1">
                                    {{ $department->created_at}}
                                     </span>
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <a  class="crud edit-client" data-department-id="{{ $department->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>
                                        <form  method="post" action="{{ route('admin.departments-expenses.destroy', $department->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="delete-item crud">
                                                <i class="fas fa-trash-alt  text-danger"></i>
                                            </a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @foreach($department->child_sections as $child_section)
                                <tr style="background-color: #FCE4EC !important;">
                                    <td>
                                        -------
                                     </td>     
                                    <td> {{ $child_section->department_name }} </td>
                                    <td> {{ $child_section->department_parent->department_name }} </td>
                                    <td>
                                        <span class="badge bg-label-primary me-1">
                                       {{ $child_section->created_at}}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a  class="crud edit-client" data-department-id="{{ $child_section->id }}">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form  method="post" action="{{ route('admin.departments-expenses.destroy', $child_section->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a class="delete-item crud">
                                                    <i class="fas fa-trash-alt  text-danger"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                   </tbody>
               </table>
           </div>
           <br/><br/>
           <div class="d-flex flex-row justify-content-center">
               {{ $other_departments->links() }}
           </div>
       </div>
   </div>
</div>
@endsection

@push('script')

<script>

jQuery('.edit-client').click(function(){
    let data_edit = jQuery(this).attr('data-department-id');
    let Popup = jQuery('#modalCenter').modal('show');
    let url = "{{ route('admin.departments-expenses.edit',':id') }}";
    url = url.replace(':id',data_edit);
    $.ajax({
        url:url,
        type:"GET",
        success: function(data){
            if(data.status == true){
                jQuery('#modal-content-inner').html(data.view);
            }
            console.log(data);
        }
    })
    console.log(Popup);
});


   jQuery('.delete-item').click(function(){

       if(confirm('هل متأكد من اتمام حذف')){
           jQuery(this).parents('form').submit();
       }
   });
</script>
@endpush
