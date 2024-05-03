<?php

use App\Http\Controllers\ProvincialCoordinator\CenterController;
use App\Http\Controllers\ProvincialCoordinator\CountryController;
use App\Http\Controllers\ProvincialCoordinator\EmployController;
use App\Http\Controllers\ProvincialCoordinator\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileSystemController;
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
           // Route::post('get_information_of_user',[CoController::class,'get_information_of_user']);

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
############################ END ########################################


############################ Folder ########################################
Route::post('AddFolder', [FileSystemController::class, 'AddFolder']);
Route::post('ShowFolder/{id}', [FileSystemController::class, 'ShowFolder']);
Route::post('getProjectFolders/{id}', [FileSystemController::class, 'getProjectFolders']);


############################ Report ########################################
Route::post('AddReport', [ReportController::class, 'AddReport']);
Route::post('ShowReports', [ReportController::class, 'ShowReports']);
Route::post('getFolderReports', [ReportController::class, 'getFolderReports']);


############################ Project ########################################
Route::post('/CreateProject', [ProjectController::class, 'CreateProject']);
Route::post('/updateProject', [ProjectController::class, 'updateProject']);
Route::post('/changeStatusProject', [ProjectController::class, 'changeStatusProject']);
Route::get('getProjects', [ProjectController::class, 'getProjects']);
Route::post('getProject', [ProjectController::class, 'getProject']);
Route::post('getBudget', [ProjectController::class, 'getBudget']);

//////////////////////////////////////////////////////////////////////



############################ Project wissam ########################################


Route::post('add_team_project', [EmployController::class, 'add_team_project']);
Route::post('get_team_project', [EmployController::class, 'get_team_project']);
Route::post('get_workflow_plan', [EmployController::class, 'get_workflow_plan']);
Route::post('confirm_task', [EmployController::class, 'confirm_task']);
Route::post('add_task', [EmployController::class, 'add_task']);
Route::post('git_project_financier', [EmployController::class, 'git_project_financier']);



