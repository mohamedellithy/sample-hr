<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeSalarie;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\DB;

class InvoicesPdfController extends Controller
{
    public function download_pdf_salary($id){
        list($month,$year) = explode('-',request()->query('month'));
        $salary = EmployeeSalarie::find($id);
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
        ->LeftJoin('employee_salaries',function($join) use($month,$year){
            $join->on('employee_salaries.employee_id','=','employees.id')
            ->whereMonth('employee_salaries.date',$month)
            ->whereYear('employee_salaries.date',$year);
        })
        ->leftJoin('employee_paids',function($join) use($month,$year){
            $join->on('employee_paids.employee_id','=','employees.id')
            ->whereMonth('employee_paids.month',$month)
            ->whereYear('employee_paids.month',$year);
        })
        ->select(
            DB::raw('sum(employee_advances.amount) as sumAdvances'),
            DB::raw('sum(distinct employee_paids.paid) as sumPaid'),
            DB::raw('sum(employee_sales.remained) as sumSales'),
            DB::raw('count(employee_attendances.id) as countAttends'),
            DB::raw('sum(employee_salaries.deduction) as sumDeduction'),
            DB::raw('sum(employee_salaries.over_time) as sumOver_time'),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%M %Y') as attendances_date"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%m') as month_path"),
            DB::raw("DATE_FORMAT(employee_attendances.attendance_date,'%Y') as year_path"),
            'employees.name','employees.salary','employees.id'
        )
        ->groupBy('month_path','year_path','attendances_date','employees.id');
        $employeeSalary = $employeeSalary->havingRaw('month_path = '.$month.' And '.'year_path = '.$year)->first();
        
        $view = \View::make('pages.employeeSalaries.pdf',compact('employeeSalary'));
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
        $pdf::Output('مرتب-'.$id.'.pdf','D');
    }
}
