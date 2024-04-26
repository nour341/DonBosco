<?php

use App\Http\Controllers\ProvincialCoordinator\CenterController;
use App\Http\Controllers\ProvincialCoordinator\CountryController;
use App\Http\Controllers\ProvincialCoordinator\EmployController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\ProvincialCoordinator\ProjectController;
use App\Http\Controllers\ProvincialCoordinator\ReportController;
use App\Http\Controllers\UserController;


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





############################ Country ########################################
Route::post('/createCountry', [CountryController::class, 'createCountry']);
Route::post('/updateCountry', [CountryController::class, 'updateCountry']);
Route::get('/getCountries', [CountryController::class, 'getCountries']);
Route::get('/getCountry/{id}', [CountryController::class, 'getCountry']);
Route::get('/getProjectsCountry/{id}', [CountryController::class, 'getProjectsCountry']);
############################ END ########################################

############################ Center ########################################
Route::post('/createCenter', [CenterController::class, 'createCenter']);
Route::post('/updateCenter', [CenterController::class, 'updateCenter']);
Route::get('/getCenters', [CenterController::class, 'getCenters']);
Route::get('/getCenter/{id}', [CenterController::class, 'getCenter']);
Route::get('/getProjectsCenter/{id}', [CenterController::class, 'getProjectsCenter']);
############################ END ########################################


############################ Folder ########################################
Route::post('AddFolder', [FileSystemController::class, 'AddFolder']);
Route::post('ShowFolders', [FileSystemController::class, 'ShowFolders']);


############################ Report ########################################
Route::post('addReport', [ReportController::class, 'AddReport']);
Route::post('ShowReports', [ReportController::class, 'ShowReports']);


############################ Project ########################################
Route::post('createProject', [ProjectController::class, 'createProject']);
Route::get('getProjects', [ProjectController::class, 'getProjects']);
Route::get('getProject/{id}', [ProjectController::class, 'getProject']);
Route::post('getBudget', [ProjectController::class, 'getBudget']);



############################ Project wissam ########################################


Route::post('add_team_project', [EmployController::class, 'add_team_project']);
Route::post('get_team_project', [EmployController::class, 'get_team_project']);
Route::post('get_workflow_plan', [EmployController::class, 'get_workflow_plan']);
Route::post('confirm_task', [EmployController::class, 'confirm_task']);
Route::post('add_task', [EmployController::class, 'add_task']);
Route::post('git_project_financier', [EmployController::class, 'git_project_financier']);
Route::post('createEmploy', [EmployController::class, 'createEmploy']);



