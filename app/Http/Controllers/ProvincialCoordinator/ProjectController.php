<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    use GeneralTrait;
    public function CreateProject(Request $request)
    {

        $validate = Validator::make($request->all(),
            [

//                'name' => 'required|unique:projects',
                'name' => 'required',
                'local_coordinator_id' => 'required|exists:users,id,role_number,1',
                'financial_management_id' => 'required|exists:users,id,role_number,2',
                'supplier_id' => 'required|exists:users,id,role_number,4',
                'short_description'=> 'required',
                'start_date'=> 'required|date',
                'end_date'=> 'required|date|after:start_date',
                'center_id'=> 'required|exists:centers,id',
            ]);


        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        try {

            $project = Project::create([
                'name' => $request->name,
                'local_coordinator_id' => $request->local_coordinator_id,
                'financial_management_id' => $request->financial_management_id,
                'supplier_id' => $request->supplier_id,
                'short_description'=> $request->short_description,
                'start_date'=>  $request->start_date,
                'end_date'=>   $request->end_date,
                'center_id'=>  $request->center_id,
            ]);

            return $this->returnSuccess("Project created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the country. Try again after some time');
        }
    }
    public function updateProject(Request $request)
    {

        $validate = Validator::make($request->all(),
            [

                'id' => 'required',
                'name' => 'required',
                'local_coordinator_id' => 'required|exists:users,id,role_number,1',
                'financial_management_id' => 'required|exists:users,id,role_number,2',
                'supplier_id' => 'required|exists:users,id,role_number,4',
                'short_description'=> 'required',
                'start_date'=> 'required|date',
                'end_date'=> 'required|date|after:start_date',
                'center_id'=> 'required|exists:centers,id',
            ]);


        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {
            $project=Project::find($request->id);
            if(!$project){
                return $this->returnError('Failed to updated the project does not exist',404);
            }

            $project->update([
                'name' => $request->name,
                'local_coordinator_id' => $request->local_coordinator_id,
                'financial_management_id' => $request->financial_management_id,
                'supplier_id' => $request->supplier_id,
                'short_description'=> $request->short_description,
                'start_date'=>  $request->start_date,
                'end_date'=>   $request->end_date,
                'center_id'=>  $request->center_id,
            ]);

            return $this->returnSuccess("project updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the project. Try again after some time');
        }

    }

    public function changeStatusProject(Request $request){

        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {
            $project=Project::find($request->id);
            if(!$project){
                return $this->returnError('Failed to updated the project does not exist',404);
            }
            $status = 1;
            if ($project->status ==1)
                $status = 0;

            $project->update([
                'status' => $status,
            ]);

            return $this->returnSuccess("project updated status Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the project. Try again after some time');
        }

    }

    public function getProjects(){

        try {
            $projects=Project::get();
            foreach ($projects as $project) {
                $project->status = $project->getStatus();
            }



            return $this->returnData('projects',$projects,'Get the all projects successfully');

        }
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the  projects. Try again after some time');
        }

    }


    public function getProject(Request $request){
        $id = $request->id;
        $project=Project::find($id);
        $project->status = $project->getStatus();
        if(!$project){
            return $this->returnError('Failed to get country. the Project does not exist',404);
        }

        return $this->returnData('project',$project,'Get the Projects successfully');
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
