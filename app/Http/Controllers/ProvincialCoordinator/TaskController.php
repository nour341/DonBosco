<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{ use GeneralTrait;
    public function add_task(Request $req)
    {
        $rules = [
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',
            'status_id' => 'required',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'project_id.required' => 'The project ID is required.',
            'project_id.integer' => 'The project ID must be an integer.',
            'project_id.exists' => 'The selected project ID does not exist.',
            'user_id.required' => 'The user ID is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user ID does not exist.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date is not a valid date.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date is not a valid date.',
            'start_date.before_or_equal' => 'The start date must be a date before or equal to the end date.',
            'status.required' => 'The status is required.',
        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {

            Task::create([
                'project_id'=>$req->project_id,
                'user_id'=>$req->user_id,
                'description'=>$req->description,
                'end_date'=>$req->end_date,
                'start_date'=>$req->start_date,
                'status_id'=>$req->status_id,
            ]);

            return $this->returnSuccess('The task has been successfully selected for the project');

        } catch (\Throwable $th) {
            return $this->returnError('Failed Add the  task. Try again after some time');

        }
    }

    public function update_task(Request $req)
    {
        $rules = [
            'task_id' => 'required|integer|exists:tasks,id',
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status_id' => 'required',
        ];

        $messages = [
            'project_id.required' => 'The project ID is required.',
            'project_id.integer' => 'The project ID must be an integer.',
            'project_id.exists' => 'The selected project ID does not exist.',
            'user_id.required' => 'The user ID is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user ID does not exist.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date is not a valid date.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date is not a valid date.',
            'start_date.before_or_equal' => 'The start date must be a date before or equal to the end date.',
            'status.required' => 'The status is required.',
        ];

        $validate = Validator::make($req->all(), $rules, $messages);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }

        try {
            $task = Task::find($req->task_id); // Find the task by ID
            $task->update([
                'project_id' => $req->project_id,
                'user_id' => $req->user_id,
                'description' => $req->description,
                'end_date' => $req->end_date,
                'start_date' => $req->start_date,
                'status_id' => $req->status_id,
            ]);

            return $this->returnSuccess('The task has been successfully updated.');

        } catch (\Throwable $th) {
            return $this->returnError('Failed to update the task. Try again after some time');
        }
    }


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
        try {

            $task=Task::find($request->task_id);
            if(!$task){
                return $this->returnError('Failed to updated the task does not exist',404);
            }

            $task->update([
                'status_id'=>$request->status_id,
            ]);


            return $this->returnSuccess("Status Task updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the status task. Try again after some time');
        }

    }

    public function deleteTask(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'task_id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        try {
            $task=Task::find($request->task_id);
            if(!$task){
                return $this->returnError('Failed to deleted the task does not exist',404);
            }

            $task->delete();

            return $this->returnSuccess("Task deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the task. Try again after some time');
        }
    }



    public function getTasksProject(Request $request){

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

        return $this->returnData('tasks', $tasks, 'Get the Tasks successfully');
    }




}
