<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ClientsSalesController;
use App\Http\Controllers\EmployeesSalesController;
use App\Http\Controllers\EmployeesAdvancesController;
use App\Http\Controllers\EmployeesSalariesController;

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
        Route::resource('employeeAdvances', EmployeesAdvancesController::class);
        Route::resource('employeeSalaries', EmployeesSalariesController::class);


        Route::post('exportEployee',[ EmployeesController::class,'exportEployee'])->name('employees.export');
        Route::post('exportSales',[ SalesController::class,'exportSales'])->name('sales.export');




    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
