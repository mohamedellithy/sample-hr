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
        $view = \View::make('pages.employeeSalaries.pdf',compact('employee'));
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
