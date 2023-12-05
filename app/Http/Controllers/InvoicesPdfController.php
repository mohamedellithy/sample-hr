<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeePaid;
use App\Models\EmployeeSalarie;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeSale;
use Carbon\Carbon;
class InvoicesPdfController extends Controller
{
    public function download_pdf_salary($id){
        list($month,$year) = explode('-',request()->query('month'));
        $employee = Employee::find($id);
        
        $employee->countAttends   =   EmployeeAttendance::where([
            'employee_id' => $id
        ])->whereMonth('employee_attendances.attendance_date',$month)
        ->whereYear('employee_attendances.attendance_date',$year)->count();


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

        $empolyee_salary = get_empolyee_price_by_month($employee,$month,$year);

        $view = \View::make('pages.employeeSalaries.pdf',compact('employee','empolyee_salary'));
        $html = $view->render();
        $pdf  = new TCPDF();
        $pdf::SetTitle('مرتب-'.$id);
        $pdf::AddPage();
        $pdf::setRTL(true);
        $pdf::SetFont('dejavusans', '', 8);
        $pdf::writeHTMLCell(0,0,'','',$html,'LRTB', 1, 0, true, 'R', false);
        $pdf::setPrintFooter(false);
        $pdf::setPrintHeader(false);
        $pdf::SetMargins(0,0,0);
        $pdf::setHeaderData('',0,'','',array(0,0,0), array(255,255,255) ); 
        $pdf::Output('مرتب-'.$employee->name.'-'.$month.'-'.$year.'.pdf','D');
    }



    public function download_employee_salaries_pdf(Request $request){
        $employees = Employee::all();
        $month     = $request->input('month');
        $year      = $request->input('year');
        $view = \View::make('pages.employeeSalaries.all_salaries_pdfs',compact('employees','month','year'));
        $html = $view->render();
        $pdf  = new TCPDF();
        $pdf::SetTitle('مرتب-');
        $pdf::AddPage();
        $pdf::setRTL(true);
        $pdf::SetFont('dejavusans', '', 8);
        $pdf::writeHTMLCell(0,0,'','',$html,'LRTB', 1, 0, true, 'R', false);
        $pdf::setPrintFooter(false);
        $pdf::setPrintHeader(false);
        $pdf::SetMargins(0,0,0);
        $pdf::setHeaderData('',0,'','',array(0,0,0), array(255,255,255) ); 
        $pdf::Output('مرتب-'.'-'.$month.'-'.$year.'.pdf','D');
    }

    public function download_daily_deals_pdf(Request $request){
        if(request()->input('datefilter')):
            $filter_date    = explode('-',request()->input('datefilter'));
            $from           = Carbon::parse(trim($filter_date[0]))->format('Y-m-d');
            $to             = Carbon::parse(trim($filter_date[1]))->format('Y-m-d');
        else:
            $from = (new Carbon('first day of this month'))->format('Y-m-d');
            $to  = (new Carbon('last day of this month'))->format('Y-m-d');
        endif;

        $date_range = $from.' / '.$to;

        //dd($from,$to);
        $items    = DB::table('expenses')
        ->Leftjoin('department_expenses As parent_department','parent_department.id','=','expenses.section')
        ->Leftjoin('department_expenses As child_department','child_department.id','=','expenses.sub_service')
        ->select('expenses.id as item_expense_id','parent_department.department_name as item_name','child_department.department_name as item_description','expenses.amount as item_amount','expenses.created_at as item_created_at')
        ->whereBetween('expenses.created_at',[
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
        $view = \View::make('pages.daily-deals.pdf',compact('data','date_range'));
        $html = $view->render();
        $pdf  = new TCPDF();
        $pdf::SetTitle('مرتب-');
        $pdf::AddPage();
        $pdf::setRTL(true);
        $pdf::SetFont('dejavusans', '', 8);
        $pdf::writeHTMLCell(0,0,'','',$html,'LRTB', 1, 0, true, 'R', false);
        $pdf::setPrintFooter(false);
        $pdf::setPrintHeader(false);
        $pdf::SetMargins(0,0,0);
        $pdf::setHeaderData('',0,'','',array(0,0,0), array(255,255,255) ); 
        $pdf::Output('قيود-يومية-'.'-'.$from.'-'.$to.'.pdf','D');
    }
}
