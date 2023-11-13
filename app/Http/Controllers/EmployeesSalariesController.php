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
                fn($query) => $query->whereBetween('date',[$from,$to])
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

        list($month,$year) = explode('-',request()->query('month'));

        $employeeSalary = DB::table('employees');
        $employeeSalary =  $employeeSalary->where('employees.id',$id)
        ->Join('employee_attendances',function($join) use($month,$year){
            $join->on('employees.id','=','employee_attendances.employee_id')
            ->whereMonth('employee_attendances.attendance_date',$month)
            ->whereYear('employee_attendances.attendance_date',$year);
        })
        ->LeftJoin('employee_advances',function($join) use($month,$year){
            $join->on('employees.id','=','employee_advances.employee_id')
            ->whereMonth('employee_advances.advance_date',$month)
            ->whereYear('employee_advances.advance_date',$year);
        })
        ->LeftJoin('employee_sales',function($join) use($month,$year){
            $join->on('employees.id','=','employee_sales.employee_id')
            ->whereMonth('employee_sales.sale_date',$month)
            ->whereYear('employee_sales.sale_date',$year);
        })

        ->leftJoin('employee_paids',function($join) use($month,$year){
            $join->on('employee_paids.employee_id','=','employees.id')
            ->whereMonth('employee_paids.month',$month)
            ->whereYear('employee_paids.month',$year);
        })
        ->leftJoin(DB::raw('(SELECT employee_id, SUM(deduction) AS totalDeduction, SUM(over_time) AS totalOverTime
                   FROM employee_salaries
                   WHERE MONTH(date) = '.$month.' AND YEAR(date) = '.$year.'
                   GROUP BY employee_id) as salary'), 'employees.id', '=', 'salary.employee_id')

        ->select(
            DB::raw('IFNULL(salary.totalDeduction, 0) as sumDeduction'),
            DB::raw('IFNULL(salary.totalOverTime, 0) as sumOver_time'),
            DB::raw('sum(distinct employee_advances.amount) as sumAdvances'),
            DB::raw('sum(distinct employee_paids.paid) as sumPaid'),
            DB::raw('sum(distinct employee_sales.remained) as sumSales'),
            DB::raw('count(distinct employee_attendances.id) as countAttends'),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%M %Y') as attendances_date"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%m') as month_path"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%Y') as year_path"),
            'employees.name','employees.salary','employees.id'
        )
        ->groupBy('salary.totalOverTime','salary.totalDeduction','month_path','year_path','attendances_date','employees.id','employees.name','employees.salary');
        $employeeSalary = $employeeSalary->havingRaw('month_path = '.$month.' And '.'year_path = '.$year)->first();
        //dd($employeeSalary);
        return view('pages.employeeSalaries.show', compact('employeeSalary'));
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
        EmployeeSalarie::find($id);
        EmployeeSalarie::destroy($id);
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
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
