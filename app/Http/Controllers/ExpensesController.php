<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportExpense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ExpensesRequest;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expenses = Expense::query();
        $per_page = 10;


        $expenses->when(request('service_filter') != null, function ($q) {
            return $q->where('service',request('service_filter'));
        });


        $expenses->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $expenses->whereBetween('expense_date',[$from,$to]);
        }

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $expenses = $expenses->paginate($per_page);
        return view('pages.expenses.index', compact('expenses'));
    }

    public function exportExpenses(Request $request){


        return Excel::download(new ExportExpense( $request->service_filter,$request->datefilter,$request->filter),'expenses.xlsx');

        return redirect()->back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesRequest $request)
    {
            $data = $request->all();

            DB::beginTransaction();

            try{
                if ($request->hasfile('attachment') ) {
                    $attachment_image = $request->file('attachment');
                    $image_name = url('').'/uploads/expense/'.time().'.' .$attachment_image->getClientOriginalExtension();
                  if($attachment_image->move(public_path('uploads/expense/'), $image_name)){

                    $data['attachment'] = $image_name;

                  }
                } else {
                    unset($data['attachment']);
                }

                Expense::create($data);

                DB::commit();
                flash('تم الاضافه بنجاح', 'success');
                return redirect()->back();
            }
            catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::find($id);
        return view('pages.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::find($id);
        return view('pages.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensesRequest $request, $id)
    {
           $expense = Expense::find($id);
            $data = $request->all();
            DB::beginTransaction();
            try{
                if ($request->hasfile('attachment') ) {
                    $attachment_image = $request->file('attachment');
                    $image_name = url('').'/uploads/expense/'.time().'.' .$attachment_image->getClientOriginalExtension();
                  if($attachment_image->move(public_path('uploads/expense/'), $image_name)){
                    // delete old img
                      $imagePath = Str::after($expense->attachment, url(url('').'/'));
                        if(File::exists($imagePath) && $expense->attachment != 'http://127.0.0.1:8000/uploads/expense/default.jpg')
                        {
                            File::delete($imagePath);
                        }

                    $data['attachment'] = $image_name;
                  }
                } else {
                    unset($data['photo']);
                }
                $expense->update($data);

                DB::commit();
                flash('تم التعديل بنجاح', 'warning');
                return redirect()->back();
            }
            catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);
        if($expense->attachment){
            $imagePath = Str::after($expense->attachment, url(url('').'/'));
            if(File::exists($imagePath) && $expense->attachment != 'http://127.0.0.1:8000/uploads/expense/default.jpg')
            {
            File::delete($imagePath);
            }
        }
        $expense->delete();
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
