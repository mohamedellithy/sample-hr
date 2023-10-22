<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeAdvance;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportEmployeeAdvances;

class EmployeesAdvancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeAdvances = EmployeeAdvance::query();
        $per_page = 10;


        if ($request->has('from') and $request->has('to') and $request->get('from') != "" and $request->get('to') != "") {
            $from=$request->get('from');
            $to=$request->get('to');

            $employeeAdvances->whereBetween('advance_date',[$from,$to]);
        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {

            $employeeAdvances->where('employee_id',$request->get('employee_filter'));
        }


        $employeeAdvances->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('advance_date', 'asc');
        },function ($q) {
            return $q->orderBy('advance_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $employeeAdvances = $employeeAdvances->paginate($per_page);
        $employees = Employee::get();
        return view('pages.employeeAdvances.index', compact('employeeAdvances','employees'));
    }

    public function exportEmployeeAdvances(Request $request){

        return Excel::download(new ExportEmployeeAdvances( $request),'EmployeeAdvances.xlsx');

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
            'amount' => ['required','numeric'],
            'advance_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        EmployeeAdvance::create($request->only([
            'employee_id',
            'amount',
            'advance_date',
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
        $employeeAdvance   = EmployeeAdvance::find($id);
        $employees = Employee::get();

        return response()->json([
            'status' => true,
            'view'   => view('pages.employeeAdvances.model.edit', compact('employeeAdvance','employees'))->render()
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
            'advance_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        EmployeeAdvance::where('id', $id)->update($request->only([
            'employee_id',
            'amount',
            'advance_date',
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
        EmployeeAdvance::find($id);
        EmployeeAdvance::destroy($id);
        return redirect()->back()->with('success_message', 'تم الحذف بنجاح');
    }
}
