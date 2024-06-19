<?php

use App\Http\Controllers\ProvincialCoordinator\StatusTaskController;
use App\Http\Controllers\SharedCenterLevel\FileSystemController;
use App\Http\Controllers\SharedCenterLevel\InvoiceController;
use App\Http\Controllers\SharedCenterLevel\ProjectController;
use App\Http\Controllers\SharedCenterLevel\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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





// Group for all roles with prefix 'center'

Route::prefix('center')->middleware(['auth:sanctum'])->group(function () {

    ########################### Folder ########################################
    Route::post('/creatFolder', [FileSystemController::class, 'creatFolder']);
    Route::post('/reNameFolder', [FileSystemController::class, 'reNameFolder']);
    Route::post('/deleteFolder', [FileSystemController::class, 'deleteFolder']);
    Route::post('/moveFolder', [FileSystemController::class, 'moveFolder']);
    Route::post('/copyFolder', [FileSystemController::class, 'copyFolder']);
    Route::post('/downloadFolderZip', [FileSystemController::class, 'downloadFolderZip']);
    Route::get('/getMainFolderProjects', [FileSystemController::class, 'getMainFolderProjects']);
    Route::post('/getChildrenFolder', [FileSystemController::class, 'getChildrenFolder']);
    ########################### End ########################################

    ########################### File ########################################
    Route::post('/addFile', [FileSystemController::class, 'addFile']);
    Route::post('/reNameFile', [FileSystemController::class, 'reNameFile']);
    Route::post('/deleteFile', [FileSystemController::class, 'deleteFile']);
    Route::post('/moveFile', [FileSystemController::class, 'moveFile']);
    Route::post('/copyFile', [FileSystemController::class, 'copyFile']);
    Route::post('/downloadFile', [FileSystemController::class, 'downloadFile']);
    ########################### End ########################################


    ########################### Project ########################################

    Route::get('/getProjects', [ProjectController::class, 'getProjects']);

    ########################### End ########################################

    Route::post('/project/getBudget', [ProjectController::class, 'getBudget']);


    Route::post('task/changeTaskStatus', [TaskController::class, 'change_task_status']);
    Route::post('task/getTasksProject', [TaskController::class, 'getTasksProject']);





    Route::post('invoice/addInvoice', [InvoiceController::class, 'addInvoice']);
    Route::get('invoice/getAllNewInvoice', [InvoiceController::class, 'getAllNewInvoice']);
    Route::get('invoice/getAllInvoiceCancelled', [InvoiceController::class, 'getAllInvoiceCancelled']);
    Route::get('invoice/getAllInvoiceConfirmed', [InvoiceController::class, 'getAllInvoiceConfirmed']);
    Route::post('invoice/getInvoiceNewByProjectID', [InvoiceController::class, 'getInvoiceNewByProjectID']);
    Route::post('invoice/getInvoiceCancelledByProjectID', [InvoiceController::class, 'getInvoiceCancelledByProjectID']);
    Route::post('invoice/getInvoiceConfirmedByProjectID', [InvoiceController::class, 'getInvoiceConfirmedByProjectID']);
    Route::post('invoice/getInvoiceNewByTaskID', [InvoiceController::class, 'getInvoiceNewByTaskID']);
    Route::post('invoice/getInvoiceCancelledByTaskID', [InvoiceController::class, 'getInvoiceCancelledByTaskID']);
    Route::post('invoice/getInvoiceConfirmedByTaskID', [InvoiceController::class, 'getInvoiceConfirmedByTaskID']);




});


Route::post('/auth/login', [UserController::class, 'login']);


#------------------------- statusTask ------------------------------------

Route::get('statusTask/getStatusTask', [StatusTaskController::class, 'getStatusTask']);
