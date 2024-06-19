<?php

namespace App\Http\Controllers\SharedCenterLevel;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{   use GeneralTrait;


    public function change_task_status(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'task_id' => 'required',
                'status_id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        $task=Task::find($request->task_id);
        if(!$task){
            return $this->returnError('Failed to updated the task does not exist',404);
        }

        #################  Test permission ###############################
        // Retrieve the project
        $project = Project::find($task->project_id);
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
        $isFinancialManagement = $project->financial_management_id === $currentUser->id;
        $isEmployee = $project->users->contains($currentUser->id);

        if (!$isLocalCoordinator && !$isFinancialManagement && !$isEmployee) {
            return $this->returnError('You do not have permission to copy a folder for this project',  );
        }
        if ($isEmployee && !$isLocalCoordinator && !$isFinancialManagement && $task->user_id !=$currentUser->id)
        {
            return $this->returnError('You do not have permission to move a folder for this project Because you didnt create it',  );
        }
        #################  end Test permission ###############################

        try {


            $task->update([
                'status_id'=>$request->status_id,
            ]);


            return $this->returnSuccess("Status Task updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the status task. Try again after some time');
        }

    }

    public function getTasksProject(Request $request)
    {
        // Validation
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);
        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $id = $request->project_id;
        // Load the project along with its tasks and the status of each task
        $project = Project::with(['tasks.status'])->find($id);

        if (!$project) {
            return $this->returnError('Failed to get project. The project does not exist', 404);
        }

        #################  Test permission ###############################

        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
        $isFinancialManagement = $project->financial_management_id === $currentUser->id;
        $isEmployee = $project->users->contains($currentUser->id);

        if (!$isLocalCoordinator && !$isFinancialManagement && !$isEmployee) {
            return $this->returnError('You do not have permission to view tasks for this project', 403);
        }

        // If the user is an employee, only return tasks assigned to them
        if ($isEmployee && !$isLocalCoordinator && !$isFinancialManagement) {
            $tasks = $project->tasks->filter(function ($task) use ($currentUser) {
                return $task->user_id === $currentUser->id;
            })->map(function ($task) {
                return [
                    'id' => $task->id,
                    'description' => $task->description,
                    'user_id' => $task->user_id,
                    'start_date' => $task->start_date,
                    'end_date' => $task->end_date,
                    'status' => $task->status ? $task->status->name : 'No status',
                ];
            });
        } else {
            // If the user is not an employee, return all tasks
            $tasks = $project->tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'description' => $task->description,
                    'user_id' => $task->user_id,
                    'start_date' => $task->start_date,
                    'end_date' => $task->end_date,
                    'status' => $task->status ? $task->status->name : 'No status',
                ];
            });
        }

        return $this->returnData('tasks', $tasks, 'Get the Tasks successfully');
    }

}
