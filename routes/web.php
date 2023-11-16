<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\InvoicesPdfController;
use App\Http\Controllers\ClientsSalesController;
use App\Http\Controllers\EmployeesSalesController;
use App\Http\Controllers\EmployeesAdvancesController;
use App\Http\Controllers\EmployeesSalariesController;
use App\Http\Controllers\EmployeeAttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect('login');
    });

    Route::group(['as' => 'admin.'],function(){

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        Route::resource('employees', EmployeesController::class);

        Route::resource('expenses', ExpensesController::class);
        Route::resource('sales', SalesController::class);
        Route::resource('employeeSales', EmployeesSalesController::class);
        Route::resource('clients', ClientsController::class);
        Route::resource('clientSales', ClientsSalesController::class);
        Route::post('client-payemnts',[ClientsSalesController::class,'client_payemnts'])->name('client-payemnts.store');
        Route::resource('employeeAdvances', EmployeesAdvancesController::class);
        Route::resource('employeeSalaries', EmployeesSalariesController::class);
        Route::resource('employeeAttendances', EmployeeAttendanceController::class);
        Route::post('add-salary/{employee_id}',[EmployeesSalariesController::class,'employee_add_salary'])->name('employee.add-salary');
        Route::post('print-salary-invoice/{id}',[InvoicesPdfController::class,'download_pdf_salary'])->name('print-salary-invoice');
        Route::resource('shifts', ShiftController::class);


        Route::post('exportEployee',[ EmployeesController::class,'exportEployee'])->name('employees.export');
        Route::post('exportSales',[ SalesController::class,'exportSales'])->name('sales.export');
        Route::post('exportExpenses',[ ExpensesController::class,'exportExpenses'])->name('expenses.export');
        Route::post('exportEmployeeSales',[ EmployeesSalesController::class,'exportEmployeeSales'])->name('employeeSales.export');
        Route::post('exportClients',[ ClientsController::class,'exportClients'])->name('clients.export');
        Route::post('exportClientSales',[ ClientsSalesController::class,'exportClientSales'])->name('clientSales.export');
        Route::post('exportEmployeeSalaries',[ EmployeesSalariesController::class,'exportEmployeeSalaries'])->name('employeeSalaries.export');
        Route::post('exportEmployeeAdvances',[ EmployeesAdvancesController::class,'exportEmployeeAdvances'])->name('employeeAdvances.export');
        Route::post('exportEmployeeAttendances',[ EmployeeAttendanceController::class,'exportEmployeeAttendances'])->name('employeeAttendances.export');
        Route::post('exportShift',[ ShiftController::class,'exportShift'])->name('shifts.export');

        Route::post('importEmployeeAttendances',[ EmployeeAttendanceController::class,'importEmployeeAttendances'])->name('employeeAttendances.import');

        Route::post('importShifts',[ ShiftController::class,'importShifts'])->name('shifts.import');
    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
