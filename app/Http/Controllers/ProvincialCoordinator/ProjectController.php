<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function CreateProject(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required:projects',
                'description'=> 'required:projects',
                'start_date'=> 'required:projects',
                'end_date'=> 'required:projects',
                'center_id'=> 'required:projects',
            ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }
        try {
            $project = Project::create([
                'name' => $request->name,
                'description'=> $request->description,
                'start_date'=>  $request->start_date,
                'end_date'=>   $request->end_date,
                'center_id'=>  $request->center_id,
            ]);

            return $this->returnSuccess("Project Created Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Project. Try again after some time');
        }
    }

    public function getProjects(){

        try {
            $projects=Project::with('projects')->get();
            $projects = [
                'projects' => $projects
            ];
            return $this->returnData($projects,'Get the all projects successfully');

        }
        catch (\Throwable $th) {
                return $this->returnError('Failed Get the  projects. Try again after some time');
        }

    }

    public function getProject($id){
        $Project=Project::find($id);
        if(!$Project){
            return $this->returnError('Failed to get Project. the Project does not exist');
        }
        $Project=Project::with('Projects')->find($id);

        $Project = [
        'Project' => $Project
        ];

        return $this->returnData($Project,'Get the Project successfully');

    }

    public function getBudget(Request $req)
    {
        $project=Project::find($req->project_id);
        if ($project) {

            $Budgets = $project->Budgets()->get();

            $arr = [
                'message' => $Budgets,
                'status' => 200
            ];
        } else {
            $arr = [
                'message' => 'Project not found',
                'status' => 404
            ];
        }

        return response($arr, $arr['status']);
    }








   // public function getProjectsService(){

//       $result=app(ProjectService::class)-> getProjects();
  //      return $result;

   //}

}
