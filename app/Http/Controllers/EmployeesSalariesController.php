<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;

class EmployeesSalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeSalaries = EmployeeSalarie::query();
        $per_page = 10;


        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {

            $employeeSalaries->where('employee_id',$request->get('employee_filter'));
        }


        $employeeSalaries->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $employeeSalaries = $employeeSalaries->paginate($per_page);
        $employees = Employee::get();
        return view('pages.employeeSalaries.index', compact('employeeSalaries','employees'));
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
            'days' => ['required','numeric'],

        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
        ]);

        EmployeeSalarie::create($request->only([
            'employee_id',
            'days',
        ]));
        return redirect()->back()->with('success_message', 'تم اضافة الايام');
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
        $employeeSalarie   = EmployeeSalarie::find($id);
        $employees = Employee::get();

        return response()->json([
            'status' => true,
            'view'   => view('pages.EmployeeSalaries.model.edit', compact('employeeSalarie','employees'))->render()
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
            'days' => ['required','numeric'],
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
        ]);

        EmployeeSalarie::where('id', $id)->update($request->only([
            'employee_id',
            'days',
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
        EmployeeSalarie::find($id);
        EmployeeSalarie::destroy($id);
        return redirect()->back()->with('success_message', 'تم الحذف بنجاح');
    }
}
