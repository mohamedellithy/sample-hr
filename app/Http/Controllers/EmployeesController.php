<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\ExportEmployee;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\EmployeeRequest;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $employees = Employee::query();
        $per_page = 10;


        $employees->when(request('search') != null, function ($q) {
            return $q->where('nationality', 'like', '%' . request('search') . '%')
            ->orWhere('name', 'like', '%' . request('search'));

        });

   /*      $employees->when(request('filter_salary') == 'sort_asc', function ($q) {
            return $q->orderBy('salary', 'asc');
        },function ($q) {
            return $q->orderBy('salary', 'desc');
        });
 */
        $employees->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });



        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $employees = $employees->paginate($per_page);

        return view('pages.employees.index', compact('employees'));
    }

    public function exportEployee(Request $request){

        return Excel::download(new ExportEmployee($request),'employee.xlsx');
        return redirect()->back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        Employee::create($request->only([
            'name',
            'nationality',
            'salary',
            'hour',
            'passport_no',
            'birthday',
            'passport_expiry',
            'card_expiry',
            'join_date'
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
        $employee = Employee::find($id);
        return view('pages.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $employee = Employee::find($id);
        return view('pages.employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
         Employee::find($id)->update($request->only([
            'name',
            'nationality',
            'salary',
            'hour',
            'passport_no',
            'birthday',
            'passport_expiry',
            'card_expiry',
            'join_date'
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
        $employee = Employee::find($id);
        $employee->delete();
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
