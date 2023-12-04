<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpensesPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyDeals extends Controller
{
    //

    public function index(){
        
        if(request('datefilter')):
            $filter_date    = explode('-',request('datefilter'));
            $from           = Carbon::parse(trim($filter_date[0]))->format('Y-m-d');
            $to             = Carbon::parse(trim($filter_date[1]))->format('Y-m-d');
        else:
            $from = (new Carbon('first day of this month'))->format('Y-m-d');
            $to  = (new Carbon('last day of this month'))->format('Y-m-d');
        endif;

        $date_range = $from.' / '.$to;

        //dd($from,$to);
        $items    = DB::table('expenses')->select('id as item_expense_id','supplier as item_name','amount as item_amount','created_at as item_created_at')
        ->whereBetween('created_at',[
            $from,
            $to
        ])->get();

        $expanses_payments = DB::table('expenses_payments')->select('id as item_expenses_payments_id','value as item_amount','created_at as item_created_at')
        ->whereBetween('created_at',[
            $from,
            $to
        ])->get();

        $money_resources   = DB::table('money_resources')->whereNotIn('type',['sales','client_payments_sales'])->select('id as item_money_resources_id','type as item_name','description as item_description','value as item_amount','created_at as item_created_at')
        ->whereBetween('created_at',[
            $from,
            $to
        ])->get();

        $sales             = DB::table('sales')->select('id as item_sales_id',DB::raw('ROUND(cash + bank + credit_sales,3) as item_amount'),'sale_date as item_created_at')
        ->whereBetween('sale_date',[
            $from,
            $to
        ])->get();

        $client_sales      = DB::table('client_payments')
        ->join('clients','clients.id','=','client_payments.client_id')
        ->select('client_payments.id as item_sales_payments_id','clients.name as item_description','client_payments.amount as item_amount','client_payments.created_at as item_created_at')
        ->whereBetween('client_payments.created_at',[
            $from,
            $to
        ])->get();

        $employee_advances = DB::table('employee_advances')
        ->join('employees','employees.id','=','employee_advances.employee_id')
        ->select('employee_advances.id as item_employee_advances_id','employees.name as item_description','employee_advances.amount as item_amount','employee_advances.advance_date as item_created_at')
        ->whereBetween('employee_advances.advance_date',[
            $from,
            $to
        ])->get();


        $employee_paids   = DB::table('employee_paids')
        ->join('employees','employees.id','=','employee_paids.employee_id')
        ->select('employee_paids.id as item_employee_paids_id','employees.name as item_description','employee_paids.month as item_month','employee_paids.paid as item_amount','employee_paids.created_at as item_created_at')
        ->whereBetween('employee_paids.created_at',[
            $from,
            $to
        ])->get();

        $data = $items->merge($expanses_payments);

        $data = $data->merge($money_resources);

        $data = $data->merge($sales);

        $data = $data->merge($client_sales);

        $data = $data->merge($employee_advances);

        $data = $data->merge($employee_paids);

        $data = $data->sortBy('created_at');
        //dd($data->sortBy('created_at'));
        return view('pages.daily-deals.index',compact('data','date_range'));
    }
}
