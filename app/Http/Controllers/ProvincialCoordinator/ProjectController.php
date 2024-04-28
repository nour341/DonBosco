<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function CreateProject(CreateProjectRequest $request)
    {
        try {
            $project = Project::create([
                'name' => $request->name,
                'description'=> $request->description,
                'start_date'=>  $request->start_date,
                'end_date'=>   $request->end_date,
                'center_id'=>  $request->center_id,
            ]);
         return ResponseHelper::success('project created successfully');

        } catch (\Throwable $th) {
            return ResponseHelper::error('Error');

        }
    }

    public function getProjects(){

        try {
            $projects=Project::query()->get()->toArray();

    return ResponseHelper::success($projects);
        }
        catch (\Throwable $th) {
            return ResponseHelper::error('Error');

        }

    }

    public function getProject($id){
        $Project=Project::find($id);
        if(!$Project){
            return ResponseHelper::error('Error');
        }
        return ResponseHelper::success($Project);
    }

    // public function getBudget(Request $req)
    // {
    //     $project=Project::find($req->project_id);
    //     if ($project) {

    //         $Budgets = $project->budget()->get();

    //         $arr = [
    //             'message' => $Budgets,
    //             'status' => 200
    //         ];
    //     } else {
    //         $arr = [
    //             'message' => 'Project not found',
    //             'status' => 404
    //         ];
    //     }

    //     return response($arr, $arr['status']);
    // }








   // public function getProjectsService(){

   //     $result=app(ProjectService::class)-> getProjects();
  //      return $result;

   //}
   }
