<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
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
        $employeeSalaries =  $employeeSalaries->Join('employee_salaries', 'employee_salaries.employee_id','=','employees.id')->select(
            DB::raw('sum(employee_salaries.advances) as sumAdvances'),
            DB::raw('sum(employee_salaries.sales) as sumSales'),
            DB::raw('sum(employee_salaries.deduction) as sumDeduction'),
            DB::raw('sum(employee_salaries.over_time) as sumOver_time'),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%M %Y') as months"),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%m-%Y') as months_path"),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%m') as monthKey"),'employees.id as employee_id','employees.name','employees.salary',
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

            ->groupBy('months','months_path','monthKey')
            ->groupBy('employees.id','employees.name','employees.salary');


            if ($request->has('rows')):
                $per_page = $request->query('rows');
            endif;
        $employeeSalaries = $employeeSalaries->paginate($per_page);

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
        $salary = EmployeeSalarie::find($id);
        $employeeSalary = DB::table('employees');
        $employeeSalary =  $employeeSalary->where('employees.id',$id)->Join('employee_salaries', 'employee_salaries.employee_id','=','employees.id')->select(
            DB::raw('sum(employee_salaries.advances) as sumAdvances'),
            DB::raw('sum(employee_salaries.sales) as sumSales'),
            DB::raw('sum(employee_salaries.deduction) as sumDeduction'),
            DB::raw('sum(employee_salaries.over_time) as sumOver_time'),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%M %Y') as months"),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%m') as month_path"),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%Y') as year_path"),
            DB::raw("DATE_FORMAT(employee_salaries.date,'%m') as monthKey"),'employees.name','employees.salary',
            )
            ->groupBy('months','month_path','year_path','monthKey')
            ->groupBy('employees.name','employees.salary');
        //$employeeSalaries = $employeeSalaries->get();
        
        $employeeSalary = $employeeSalary->havingRaw('month_path = '.$month.' And '.'year_path = '.$year)->first();
        //dd($employeeSalaries);
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
}
