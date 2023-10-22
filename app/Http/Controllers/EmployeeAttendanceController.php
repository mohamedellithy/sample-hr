<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\AttendanceRequest;
use App\Exports\ExportEmployeeAttendances;
use App\Imports\ImportEmployeeAttendances;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeAttendances = EmployeeAttendance::query();
        $per_page = 10;
        if ($request->has('from') and $request->has('to') and $request->get('from') != "" and $request->get('to') != "") {
            $from=$request->get('from');
            $to=$request->get('to');

            $employeeAttendances->whereBetween('attendance_date',[$from,$to]);
        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {
            $employeeAttendances->where('employee_id',$request->get('employee_filter'));
        }

        if ($request->has('in') and $request->get('in') != "") {
            $employeeAttendances->where('clock_in', 'like', '%' .$request->get('in') . '%');
        }
        if ($request->has('out') and $request->get('out') != "") {
            $employeeAttendances->where('clock_out', 'like', '%' .$request->get('out') . '%');
        }


        $employeeAttendances->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('attendance_date', 'asc');
        },function ($q) {
            return $q->orderBy('attendance_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $employeeAttendances = $employeeAttendances->paginate($per_page);
        $employees = Employee::get();
        return view('pages.employeeAttendances.index', compact('employeeAttendances','employees'));
    }


    public function exportEmployeeAttendances(Request $request){

        return Excel::download(new ExportEmployeeAttendances( $request),'employeeAttendances.xlsx');
         return redirect()->back();

     }

     public function importEmployeeAttendances(Request $request){

         Excel::import(new ImportEmployeeAttendances,$request->file);
         return redirect()->back()->with('success_message', 'تم اضافه الملف بنجاح');

     }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::get();
        return view('pages.employeeAttendances.create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceRequest $request)
    {
        EmployeeAttendance::create($request->only([
            'employee_id',
            'attendance_date',
            'clock_in',
            'clock_out',
        ]));
        return redirect()->back()->with('success_message', 'تم اضافة بنجاح');
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
        $employeeAttendance = EmployeeAttendance::find($id);
        $employees = Employee::get();

        return view('pages.employeeAttendances.edit', compact('employeeAttendance',  'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttendanceRequest $request, $id)
    {
        EmployeeAttendance::find($id)->update($request->only([
            'employee_id',
            'attendance_date',
            'clock_in',
            'clock_out',
        ]));
        return redirect()->back()->with('success_message', 'تم تعديل بنجاح');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $EmployeeAttendance = EmployeeAttendance::find($id);
        $EmployeeAttendance->delete();
        return redirect()->back()->with('success_message', 'تم الحذف بنجاح');
    }
}
