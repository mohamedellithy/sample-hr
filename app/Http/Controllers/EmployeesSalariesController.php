<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeePaid;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportEmployeeSalaries;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeSale;

class EmployeesSalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = 10;
        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
        }
        $employeeSalaries = DB::table('employees');
        $employeeSalaries =  $employeeSalaries
        ->Join('employee_attendances',function($join){
            $join->on('employees.id','=','employee_attendances.employee_id');
        })
        ->select(
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%M %Y') as attendances_date"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%m') as month_path"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%Y') as year_path"),
            'employees.name','employees.salary','employees.id'
        )->when(
                $request->employee_filter,
                fn($query) => $query->where('employee_salaries.employee_id', $request->employee_filter)
            )
            ->when(
                $request->datefilter,
                fn($query) => $query->whereBetween('employee_attendances.attendance_date',[$from,$to])
            )
            ->when(
                $request->filter,
                fn($query) => $query->orderBy('date',$request->filter)
            )

            ->groupBy('attendances_date','month_path','year_path')
            ->groupBy('employees.id','employees.name','employees.salary');


            if ($request->has('rows')):
                $per_page = $request->query('rows');
            endif;
        $employeeSalaries = $employeeSalaries->paginate($per_page);

        //dd($employeeSalaries->get());

        $employees = Employee::get();
        return view('pages.employeeSalaries.index', compact('employeeSalaries','employees'));
    }

    public function exportEmployeeSalaries(Request $request){


        return Excel::download(new ExportEmployeeSalaries( $request),'employeeSalaries.xlsx');

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
        list($month,$year) = explode('-',request()->query('month'));
        $employee = Employee::find($id);

        $employee->countAttends   =   EmployeeAttendance::where([
            'employee_id' => $id
        ])->whereMonth('employee_attendances.attendance_date',$month)
        ->whereYear('employee_attendances.attendance_date',$year)->count() ?: 0;


        $employee->sumAdvances = EmployeeAdvance::where([
            'employee_id' => $id
        ])->whereMonth('employee_advances.advance_date',$month)
        ->whereYear('employee_advances.advance_date',$year)->sum('amount');

        $employee->sumSales = EmployeeSale::where([
            'employee_id' => $id
        ])->whereMonth('employee_sales.sale_date',$month)
        ->whereYear('employee_sales.sale_date',$year)->sum('remained');

        $employee->sumDeduction = EmployeeSalarie::where([
            'employee_id' => $id
        ])->whereMonth('employee_salaries.date',$month)
        ->whereYear('employee_salaries.date',$year)->sum('deduction');

        $employee->sumOver_time = EmployeeSalarie::where([
            'employee_id' => $id
        ])->whereMonth('employee_salaries.date',$month)
        ->whereYear('employee_salaries.date',$year)->sum('over_time');

        $employee->sumPaid = EmployeePaid::where([
            'employee_id' => $id
        ])->whereMonth('employee_paids.month',$month)
        ->whereYear('employee_paids.month',$year)->sum('paid');

        $employee->month_path =  $month;
        $employee->year_path  =  $year;
        return view('pages.employeeSalaries.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function employee_add_salary(Request $request,$employee_id){
        EmployeePaid::create([
            'employee_id' => $employee_id,
            'month'       => $request->input('monthes'),
            'paid'        => $request->input('value')
        ]);

        return back();
    }
}
