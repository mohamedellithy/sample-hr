<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSale;
use Illuminate\Http\Request;

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


        if ($request->has('from') and $request->has('to') and $request->get('from') != "" and $request->get('to') != "") {
            $from=$request->get('from');
            $to=$request->get('to');

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
            'amount' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        EmployeeSale::create($request->only([
            'employee_id',
            'amount',
            'sale_date',
        ]));
        return redirect()->back()->with('success_message', 'تم اضافة المبايعه');
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
            'amount' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        EmployeeSale::where('id', $id)->update($request->only([
            'employee_id',
            'amount',
            'sale_date',
        ]));
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
        EmployeeSale::find($id);
        EmployeeSale::destroy($id);
        return redirect()->back()->with('success_message', 'تم الحذف بنجاح');
    }
}
