<?php

use App\Http\Controllers\ProvincialCoordinator\CenterController;
use App\Http\Controllers\ProvincialCoordinator\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

//Route::group(["middleware"=>['auth:sanctum']],function()
//        {
//           // Route::post('get_information_of_user',[CoController::class,'get_information_of_user']);
//
//
//        });


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
Route::post('ShowFolder/{id}', [FileSystemController::class, 'ShowFolder']);
Route::post('getProjectFolders/{id}', [FileSystemController::class, 'getProjectFolders']);


############################ Report ########################################
Route::post('AddReport', [ReportController::class, 'AddReport']);
Route::post('ShowReports', [ReportController::class, 'ShowReports']);
Route::post('getFolderReports', [ReportController::class, 'getFolderReports']);


############################ Project ########################################
Route::post('/CreateProject', [ProjectController::class, 'CreateProject']);
Route::get('getProjects', [ProjectController::class, 'getProjects']);
Route::post('getProject/{id}', [ProjectController::class, 'getProject']);
Route::post('getBudget', [ProjectController::class, 'getBudget']);
