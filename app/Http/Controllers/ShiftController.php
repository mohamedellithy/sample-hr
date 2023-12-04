<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use App\Exports\ExportShift;
use App\Imports\ImportShift;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftRequest;
use Maatwebsite\Excel\Facades\Excel;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shifts = Shift::query();
        $per_page = 10;


        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $shifts->whereBetween('date',[$from,$to]);
        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {
            $shifts->where('employee_id',$request->get('employee_filter'));
        }

        if ($request->has('in') and $request->get('in') != "") {
            $shifts->where('clock_in', 'like', '%' .$request->get('in') . '%');
        }
        if ($request->has('out') and $request->get('out') != "") {
            $shifts->where('clock_out', 'like', '%' .$request->get('out') . '%');
        }


        $shifts->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('date', 'asc');
        },function ($q) {
            return $q->orderBy('date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $shifts = $shifts->paginate($per_page);
        $employees = Employee::get();
        return view('pages.shifts.index', compact('shifts','employees'));
    }

    public function exportShift(Request $request){

        return Excel::download(new ExportShift( $request),'shifts.xlsx');
         return redirect()->back();

     }

    public function importShifts(Request $request){

        $shift = new ImportShift();
        $data  = Excel::import($shift,$request->file);
        $new_shifts = Shift::whereIn('id',$shift->collects_shifts)->paginate(10);
        flash('تم الاضافه بنجاح', 'success');
        session(['new_shifts' => $new_shifts]);
        return redirect()->back();
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::get();
        return view('pages.shifts.create',compact('employees'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShiftRequest $request)
    {

        $Shift= Shift::where('employee_id',$request->employee_id)->where('date',$request->date)->first();
        if($Shift){

          flash('هذا التاريخ موجود من قبل', 'warning');
          return redirect()->back();
        }


        Shift::create($request->only([
            'employee_id',
            'date',
            'clock_in',
            'clock_out',
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
        $shift = Shift::find($id);
        $employees = Employee::get();

        return view('pages.shifts.edit', compact('shift',  'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShiftRequest $request, $id)
    {

    $Shift= Shift::where('employee_id',$request->employee_id)->where('date',$request->date)->first();

        if($Shift && $Shift->id!=$id){

          flash('هذا التاريخ موجود من قبل', 'warning');
          return redirect()->back();
        }
        Shift::find($id)->update($request->only([
            'employee_id',
            'date',
            'clock_in',
            'clock_out',
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
        $Shift = Shift::find($id);
        $Shift->delete();
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
