<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\EmployeesController;

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


    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
