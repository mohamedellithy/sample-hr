<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\AttendanceRequest;
use App\Exports\ExportEmployeeAttendances;
use App\Imports\ImportEmployeeAttendances;
use App\Services\CalculateHourSalaryService;
use App\Services\DeductionsAndOvertimeService;

class EmployeeAttendanceController extends Controller
{

    protected $CalcSalSE;
    protected $attendanceService;

    public function __construct(CalculateHourSalaryService $CalcSalSE,DeductionsAndOvertimeService $attendanceService)
    {
        $this->CalcSalSE = $CalcSalSE;
        $this->attendanceService = $attendanceService;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeAttendances = EmployeeAttendance::query();
        $per_page = 10;


        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
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


        DB::beginTransaction();
        try{
            if ($request->hasFile('file')){
                $updateFile = $request->file('file');

                $path = $updateFile->getRealPath();
                $fileExtension = $updateFile->getClientOriginalExtension();
                $formats = ['xls', 'xlsx', 'ods', 'csv'];
                if (! in_array($fileExtension, $formats)) {
                    flash('Only supports upload .xlsx, .xls files', 'error');
                    return redirect()->back();
                }

                $import= Excel::import(new ImportEmployeeAttendances($this->attendanceService),$request->file);
                DB::commit();

            flash(' تم اضافه الملف بنجاح', 'success');
            return redirect()->back();

           }
        }
        catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }


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

        $attendance= EmployeeAttendance::where('employee_id',$request->employee_id)->where('attendance_date',$request->attendance_date)->first();
      if($attendance){
        flash('هذا التاريخ موجود من قبل', 'warning');
        return redirect()->back();
      }

        $employeeAttendance = EmployeeAttendance::create($request->only([
            'employee_id',
            'attendance_date',
            'clock_in',
            'clock_out',
        ]));

       $this->attendanceService->calculateDeductionsAndOvertime($request);


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

        $attendance= EmployeeAttendance::where('employee_id',$request->employee_id)->where('attendance_date',$request->attendance_date)->first();

        if($attendance && $attendance->id!=$id){

          flash('هذا التاريخ موجود من قبل', 'warning');
          return redirect()->back();
        }

        $EmployeeAttendance= EmployeeAttendance::find($id);
        $salary= EmployeeSalarie::where('employee_id',$EmployeeAttendance->employee_id)->where('date',$EmployeeAttendance->attendance_date)->first();

        if($salary){
            $salary->deduction = 0;
            $salary->over_time = 0;
            $salary->save();
        }

        EmployeeAttendance::find($id)->update($request->only([
            'employee_id',
            'attendance_date',
            'clock_in',
            'clock_out',
        ]));

        $this->attendanceService->calculateDeductionsAndOvertime($request);

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
        $EmployeeAttendance = EmployeeAttendance::find($id);
        $salary= EmployeeSalarie::where('employee_id',$EmployeeAttendance->employee_id)->where('date',$EmployeeAttendance->attendance_date)->first();
        if($salary){
            $salary->deduction = 0;
            $salary->over_time = 0;
            $salary->save();
        }

        $EmployeeAttendance->delete();
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
