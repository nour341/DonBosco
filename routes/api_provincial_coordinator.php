<?php

use App\Http\Controllers\ProvincialCoordinator\CenterController;
use App\Http\Controllers\ProvincialCoordinator\CountryController;
use App\Http\Controllers\ProvincialCoordinator\DashboardController;
use App\Http\Controllers\ProvincialCoordinator\EmployController;
use App\Http\Controllers\ProvincialCoordinator\FileSystemController;
use App\Http\Controllers\ProvincialCoordinator\InvoiceController;
use App\Http\Controllers\ProvincialCoordinator\ItemController;
use App\Http\Controllers\ProvincialCoordinator\StatusTaskController;
use App\Http\Controllers\ProvincialCoordinator\TaskController;
use App\Http\Controllers\ProvincialCoordinator\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvincialCoordinator\ProjectController;
use App\Http\Controllers\ProvincialCoordinator\ReportController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|*/

Route::group(["middleware"=>['auth:sanctum'],'type.provincial'],function()
{
    //
});

############################ User ########################################
Route::get('/getRole', [UserController::class, 'getRole']);
Route::post('createUser', [UserController::class, 'createUser']);
Route::post('updateRolById', [UserController::class, 'updateRolById']);
Route::post('deleteUser', [UserController::class, 'deleteUser']);
Route::post('getUsersByRole', [UserController::class, 'getUsersByRole']);
Route::post('getAllUsers', [UserController::class, 'getAllUsers']);
############################ END ########################################

############################ Country ########################################
Route::post('/createCountry', [CountryController::class, 'createCountry']);
Route::post('/updateCountry', [CountryController::class, 'updateCountry']);
Route::post('/deleteCountry', [CountryController::class, 'deleteCountry']);
Route::get('/getCountries', [CountryController::class, 'getCountries']);
Route::post('/getCountry', [CountryController::class, 'getCountry']);
Route::post('/getProjectsCountry', [CountryController::class, 'getProjectsCountry']);
Route::post('/getCentersCountry', [CountryController::class, 'getCentersCountry']);
############################ END ########################################

############################ Center ########################################
Route::post('/createCenter', [CenterController::class, 'createCenter']);
Route::post('/updateCenter', [CenterController::class, 'updateCenter']);
Route::post('/deleteCenter', [CenterController::class, 'deleteCenter']);
Route::get('/getCenters', [CenterController::class, 'getCenters']);
Route::post('/getCenter', [CenterController::class, 'getCenter']);
Route::post('/getProjectsCenter', [CenterController::class, 'getProjectsCenter']);
Route::post('/getLocalsCenter', [CenterController::class, 'getLocalsCenter']);
Route::post('/getFinancialsCenter', [CenterController::class, 'getFinancialsCenter']);
Route::post('/getEmployeesCenter', [CenterController::class, 'getEmployeesCenter']);
############################ END ########################################

########################### Folder ########################################
Route::post('creatFolder', [FileSystemController::class, 'creatFolder']);
Route::post('reNameFolder', [FileSystemController::class, 'reNameFolder']);
Route::post('downloadFolderZip', [FileSystemController::class, 'downloadFolderZip']);
Route::post('deleteFolder', [FileSystemController::class, 'deleteFolder']);
Route::get('getMainFolderProjects', [FileSystemController::class, 'getMainFolderProjects']);
Route::post('getChildrenFolder', [FileSystemController::class, 'getChildrenFolder']);
Route::post('moveFolder', [FileSystemController::class, 'moveFolder']);
Route::post('copyFolder', [FileSystemController::class, 'copyFolder']);
############################ END ########################################


########################### File ########################################
Route::post('addFile', [FileSystemController::class, 'addFile']);
Route::post('reNameFile', [FileSystemController::class, 'reNameFile']);
Route::post('downloadFile', [FileSystemController::class, 'downloadFile']);
Route::post('deleteFile', [FileSystemController::class, 'deleteFile']);
Route::post('copyFile', [FileSystemController::class, 'copyFile']);
Route::post('moveFile', [FileSystemController::class, 'moveFile']);
############################ END ########################################


############################ Project ########################################
Route::post('/CreateProject', [ProjectController::class, 'CreateProject']);
Route::post('/updateProject', [ProjectController::class, 'updateProject']);
Route::post('/changeStatusProject', [ProjectController::class, 'changeStatusProject']);
Route::get('getProjects', [ProjectController::class, 'getProjects']);
Route::post('getProject', [ProjectController::class, 'getProject']);

#------------------------- Employ ------------------------------------
Route::post('addEmployeeToProject', [EmployController::class, 'addEmployeeToProject']);
Route::post('removeEmployeeFromProject', [EmployController::class, 'removeEmployeeFromProject']);
Route::post('get_team_project', [EmployController::class, 'getEmployeesProject']);
############################ END ########################################


############################ Budget #####################################
Route::post('project/getBudget', [ProjectController::class, 'getBudgetProject']);
Route::post('project/addItemBudget', [ProjectController::class, 'addItemBudget']);
Route::post('project/addBudget', [ProjectController::class, 'addBudget']);
Route::post('project/updateItemBudget', [ProjectController::class, 'updateItemBudget']);
Route::post('project/updateBudget', [ProjectController::class, 'updateBudget']);
Route::post('project/deleteItemBudget', [ProjectController::class, 'deleteItemBudget']);
Route::post('project/deleteListBudget', [ProjectController::class, 'deleteListBudget']);
############################ END ########################################


########################### Item ########################################
Route::post('Item/create', [ItemController::class, 'create']);
Route::post('Item/update', [ItemController::class, 'update']);
Route::post('Item/delete', [ItemController::class, 'delete']);
Route::get('Item/get', [ItemController::class, 'getItems']);
Route::post('Item/getItem', [ItemController::class, 'getItem']);
############################ END ########################################


############################ Task #######################################
Route::post('task/add', [TaskController::class, 'add_task']);
Route::post('task/update', [TaskController::class, 'update_task']);
Route::post('task/changeTaskStatus', [TaskController::class, 'change_task_status']);
Route::post('task/delete', [TaskController::class, 'deleteTask']);
Route::post('task/getTasksProject', [TaskController::class, 'getTasksProject']);

#------------------------- statusTask ------------------------------------
Route::post('statusTask/create', [StatusTaskController::class, 'create']);
Route::post('statusTask/update', [StatusTaskController::class, 'update']);
Route::post('statusTask/delete', [StatusTaskController::class, 'delete']);
############################ END ########################################


############################ Invoice ########################################
Route::post('invoice/addInvoice', [InvoiceController::class, 'addInvoice']);
Route::post('invoice/confirmInvoice', [InvoiceController::class, 'confirmInvoice']);
Route::post('invoice/cancelInvoice', [InvoiceController::class, 'cancelInvoice']);
Route::get('invoice/getAllNewInvoice', [InvoiceController::class, 'getAllNewInvoice']);
Route::get('invoice/getAllInvoiceCancelled', [InvoiceController::class, 'getAllInvoiceCancelled']);
Route::get('invoice/getAllInvoiceConfirmed', [InvoiceController::class, 'getAllInvoiceConfirmed']);
Route::post('invoice/getInvoiceNewByProjectID', [InvoiceController::class, 'getInvoiceNewByProjectID']);
Route::post('invoice/getInvoiceCancelledByProjectID', [InvoiceController::class, 'getInvoiceCancelledByProjectID']);
Route::post('invoice/getInvoiceConfirmedByProjectID', [InvoiceController::class, 'getInvoiceConfirmedByProjectID']);
Route::post('invoice/getInvoiceNewByTaskID', [InvoiceController::class, 'getInvoiceNewByTaskID']);
Route::post('invoice/getInvoiceCancelledByTaskID', [InvoiceController::class, 'getInvoiceCancelledByTaskID']);
Route::post('invoice/getInvoiceConfirmedByTaskID', [InvoiceController::class, 'getInvoiceConfirmedByTaskID']);
Route::post('invoice/getInvoiceConfirmedByDateRange', [InvoiceController::class, 'getInvoiceConfirmedByDateRange']);
Route::post('invoice/getInvoiceConfirmedByProjectAndDateRange', [InvoiceController::class, 'getInvoiceConfirmedByProjectAndDateRange']);
Route::post('invoice/getInvoiceMonthlyExpensesConfirmedByProjectID', [InvoiceController::class, 'getInvoiceMonthlyExpensesConfirmedByProjectID']);



############################ dashboard ###############################
Route::get('getStats', [DashboardController::class, 'get_stats']);






