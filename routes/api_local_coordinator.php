<?php

use App\Http\Controllers\LocalCoordinator\EmployController;
use App\Http\Controllers\LocalCoordinator\InvoiceController;
use App\Http\Controllers\LocalCoordinator\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["middleware"=>['auth:sanctum'],'type.local'],function()
        {Route::group(["prefix"=>'local'],function()
        {
            Route::post('/createEmployee',[EmployController::class,'createEmployee']);
            Route::get('/getAllUsersInCenter',[EmployController::class,'getAllUsersInCenter']);
            Route::get('/getEmployeeInCenter',[EmployController::class,'getEmployeeInCenter']);
            Route::post('/addEmployeeToProject',[EmployController::class,'addEmployeeToProject']);
            Route::post('/removeEmployeeFromProject',[EmployController::class,'removeEmployeeFromProject']);
            Route::post('/getEmployeesProject',[EmployController::class,'getEmployeesProject']);



            Route::post('/task/add',[TaskController::class,'add_task']);
            Route::post('/task/update',[TaskController::class,'update_task']);
            Route::post('/task/delete',[TaskController::class,'delete_task']);



            Route::post('/invoice/confirmInvoice',[InvoiceController::class,'confirmInvoice']);
            Route::post('/invoice/cancelInvoice',[InvoiceController::class,'cancelInvoice']);
            Route::post('/invoice/getInvoiceMonthlyExpensesConfirmedByProjectID',[InvoiceController::class,'getInvoiceMonthlyExpensesConfirmedByProjectID']);
//            Route::post('/invoice/getInvoiceCancelledByProjectID',[InvoiceController::class,'getInvoiceCancelledByProjectID']);
//            Route::post('/invoice/getAllInvoiceCancelled',[InvoiceController::class,'getAllInvoiceCancelled']);
//            Route::post('/invoice/getInvoiceCancelledByTaskID',[InvoiceController::class,'getInvoiceCancelledByTaskID']);

        });  });
