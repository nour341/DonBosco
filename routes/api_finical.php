<?php

use App\Http\Controllers\FinancialManagement\InvoiceController;
use App\Http\Controllers\FinancialManagement\ReportController;
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

Route::group(["middleware"=>['auth:sanctum'],'type.financial'],function()
{ Route::group(["prefix"=>'financial'],function()
{
    Route::post('/invoice/confirmInvoice',[InvoiceController::class,'confirmInvoice']);
    Route::post('/invoice/cancelInvoice',[InvoiceController::class,'cancelInvoice']);
    Route::post('/invoice/getInvoiceMonthlyExpensesConfirmedByProjectID',[ReportController::class,'getInvoiceMonthlyExpensesConfirmedByProjectID']);


});
});
