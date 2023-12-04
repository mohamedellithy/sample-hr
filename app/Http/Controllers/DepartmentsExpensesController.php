<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentExpenses;
use Illuminate\Validation\Rule;
class DepartmentsExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $main_departments  = DepartmentExpenses::where('parent_id',null)->get();
        $other_departments = DepartmentExpenses::query();
        $other_departments = $other_departments->with('child_sections')->where('parent_id',null);
        $per_page = 10;


        $other_departments->when(request('search') != null, function ($q) {
            return $q->where('department_name','like','%'.request('search').'%')
            ->orWhereHas('department_parent',function($query){
                $query->where('department_name','like','%'.request('search').'%');
            });
        });

        $other_departments->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $other_departments = $other_departments->paginate($per_page);

        return view('pages.departmentsExpense.index',compact('main_departments','other_departments'));
    }

    public function departments_export(){

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
        //
        $request->validate([
            'department_name' => [
                'required',
                Rule::unique('department_expenses','department_name')->where(function($query){
                    $query->where('parent_id',request('parent_id'));
                })
            ]
        ]);

        DepartmentExpenses::create([
            'department_name' => $request->input('department_name'),
            'parent_id'       => $request->input('parent_id')
        ]);

        flash('تم الاضافه بنجاح', 'success');
        return back();
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
        //
        $department_expense = DepartmentExpenses::find($id);
        $main_departments  = DepartmentExpenses::where('parent_id',null)->get();
        return response()->json([
            'status' => true,
            'view'   => view('pages.departmentsExpense.model.department', compact('department_expense','main_departments'))->render()
        ]);
    }

    public function get_sub_departments($parent_id){
        $sub_departments =  DepartmentExpenses::where('parent_id',$parent_id)->select('id','department_name')->get();
        return response()->json([
            'sub_departments' => $sub_departments
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
        //

        $request->validate([
            'department_name' => [
                'required',
                Rule::unique('department_expenses','department_name')->ignore($id)->where(function($query){
                    $query->where('parent_id',request('parent_id'));
                })
            ]
        ]);

        DepartmentExpenses::where([
            'id' => $id
        ])->update([
            'department_name' => $request->input('department_name'),
            'parent_id'       => $request->input('parent_id')
        ]);

        flash('تم تحديث بنجاح', 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        DepartmentExpenses::where([
            'id' => $id
        ])->delete();

        flash('تم حذف بنجاح', 'success');
        return back();
    }
}
