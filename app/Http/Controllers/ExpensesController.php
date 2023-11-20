<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportExpense;
use App\Exports\ExportExpensePayments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ExpensesRequest;
use App\Models\ExpensesPayment;
use App\Models\DepartmentExpenses;

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
        
        $expenses->with('department_sub','department_main');
        $expenses->when(request('search') != null, function ($q) {
            return $q->whereHas('department_main',function($query){
                $query->where('department_expenses.department_name','like','%'.request('search').'%');
            })
            ->orWhereHas('department_sub',function($query){
                $query->where('department_expenses.department_name','like','%'.request('search').'%');
            })
            ->orWhere('bill_no','like','%'.request('search').'%')
            ->orWhere('supplier','like','%'.request('search').'%');
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

        $expenses->withSum('payments','value');

        $expenses = $expenses->paginate($per_page);

        return view('pages.expenses.index', compact('expenses'));
    }

    public function expenses_payments(Request $request,$expense_id){
        $expense_payments = ExpensesPayment::query();
        $expense_payments = $expense_payments->where('expense_id',$expense_id);
        $per_page = 10;


        $expense_payments->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $expense_payments->whereBetween('created_at',[$from,$to]);
        }

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $expense_payments = $expense_payments->paginate(10);
        $expense  = Expense::withSum('payments','value')->find($expense_id);
        return view('pages.expenses.payments',compact('expense_payments','expense'));
    }

    public function expense_add_payments(Request $request,$expense_id){
        ExpensesPayment::create([
            'expense_id' => $expense_id,
            'value'      => $request->input('value')
        ]);

        flash('تم الاضافه بنجاح', 'success');

        return back();
    }

    public function exportExpensePayments(Request $request){
        return Excel::download(new ExportExpensePayments($request->search,$request->datefilter,$request->filter),'expense-payments.xlsx');

        return redirect()->back();
    }

    public function exportExpenses(Request $request){


        return Excel::download(new ExportExpense($request->search,$request->datefilter,$request->filter),'expenses.xlsx');

        return redirect()->back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $main_departments  = DepartmentExpenses::where('parent_id',null)->get();
        return view('pages.expenses.create',compact('main_departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesRequest $request)
    {
        
            $data = $request->only([
                'section',
                'sub_service',
                'bill_no',
                'supplier',
                'amount',
                'expense_description',
                'expense_date'
            ]);

            DB::beginTransaction();

            try{

                $expense = Expense::create($data);

                ExpensesPayment::create([
                    'expense_id' => $expense->id,
                    'value'      => $request->input('paid_amount')
                ]);

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
        $expense = Expense::withSum('payments','value')->find($id);
        $main_departments  = DepartmentExpenses::where('parent_id',null)->get();
        return view('pages.expenses.edit', compact('expense','main_departments'));
    }

    public function expense_payments_edit($id){
        $expense_payment = ExpensesPayment::find($id);
        $expense = Expense::where([
            'id' => $expense_payment->expense_id
        ])->withSum('payments','value')->first();
        return response()->json([
            'status' => true,
            'view'   => view('pages.expenses.model.payment-edit', compact('expense_payment','expense'))->render()
        ]);
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
            $data = $request->only([
                'section',
                'sub_service',
                'bill_no',
                'supplier',
                'amount',
                'paid_amount',
                'pending_amount',
                'expense_description',
                'expense_date'
            ]);
            DB::beginTransaction();
            try{
                $expense->update($data);

                ExpensesPayment::where([
                    'expense_id' => $expense->id
                ])->update([
                    'value'      => $request->input('paid_amount')
                ]);

                DB::commit();
                flash('تم التعديل بنجاح', 'warning');
                return redirect()->back();
            }
            catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

    }

    public function expense_payments_update(Request $request,$payment_id){
        ExpensesPayment::where([
            'id' => $payment_id
        ])->update([
            'value'      => $request->input('value')
        ]);

        flash('تم التعديل بنجاح', 'warning');
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
        $expense = Expense::find($id);
        $expense->delete();
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
