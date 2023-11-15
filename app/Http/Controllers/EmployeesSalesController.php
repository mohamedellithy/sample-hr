<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeSale;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use App\Exports\ExportEmployeeSales;
use Maatwebsite\Excel\Facades\Excel;

class EmployeesSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeSales = EmployeeSale::query();
        $per_page = 10;




        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $employeeSales->whereBetween('sale_date',[$from,$to]);
        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {

            $employeeSales->where('employee_id',$request->get('employee_filter'));
        }


        $employeeSales->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $employeeSales = $employeeSales->paginate($per_page);
        $employees = Employee::get();
        return view('pages.employeeSales.index', compact('employeeSales','employees'));
    }


    public function exportEmployeeSales(Request $request){


        return Excel::download(new ExportEmployeeSales( $request->employee_filter,$request->datefilter,$request->filter),'expenses.xlsx');

        return redirect()->back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'remained' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        EmployeeSale::create($request->only([
            'employee_id',
            'remained',
            'sale_date',
        ]));


        flash('تم الاضافه بنجاح', 'success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employeeSale   = EmployeeSale::find($id);
        $employees = Employee::get();

        return response()->json([
            'status' => true,
            'view'   => view('pages.employeeSales.model.edit', compact('employeeSale','employees'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
            'remained' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        
        EmployeeSale::where('id', $id)->update($request->only([
            'employee_id',
            'remained',
            'sale_date',
        ]));

        flash('تم التعديل بنجاح', 'warning');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $EmployeeSale=EmployeeSale::find($id);
        $salary= EmployeeSalarie::where('employee_id',$EmployeeSale->employee_id)->where('date',$EmployeeSale->sale_date)->first();
        if($salary){
            $salary->sales -= $EmployeeSale->remained;
            $salary->save();
        }
        EmployeeSale::destroy($id);
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
