<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\ProjectUser;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class EmployController extends Controller
{   use GeneralTrait;
    public function add_team_project(Request  $req)
    {

        $rules = [
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',

        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'project_id.required' => 'The project_id field is required.',
            'project_id.integer' => 'The project_id must be an integer.',
            'project_id.exists' => 'The selected project_id does not exist.',
            'user_id.required' => 'The user_id field is required.',
            'user_id.integer' => 'The user_id must be an integer.',
            'user_id.exists' => 'The selected user_id does not exist.',

        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }

        try {

            ProjectUser::create([
                'project_id'=>$req->project_id,
                'user_id'=>$req->user_id,
            ]);

            return $this->returnSuccess('The employee has been successfully selected for the project');


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }



    }

    public function get_team_project(Request  $req)
    {
        $rules = [
            'project_id' => 'required|integer|exists:projects,id',
        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'project_id.required' => 'The project_id field is required.',
            'project_id.integer' => 'The project_id must be an integer.',
            'project_id.exists' => 'The selected project_id does not exist.',
        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }

        $project=Project::find($req->project_id);
        if(!$project){
            return $this->returnError('Failed to get project. the project does not exist',404);
        }
        // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
        $users = $project->users()->get();

        return $this->returnData('users',$users,'Get the Tasks successfully');

    }

    public function add_task(Request $req)
    {
        $rules = [
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',
            'description' => 'required|string|max:255',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_date' => 'required|date|before_or_equal:end_date',
            'status' => 'required',
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
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }
        try {

            Task::create([
                'project_id'=>$req->project_id,
                'user_id'=>$req->user_id,
                'description'=>$req->description,
                'end_date'=>$req->end_date,
                'start_date'=>$req->start_date,
                'status'=>$req->status,
            ]);

            return $this->returnSuccess('The task has been successfully selected for the project');

        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }

    }
    public function get_workflow_plan(Request $req)
    {
        $rules = [
            'project_id' => 'required|integer|exists:projects,id',
        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'project_id.required' => 'The project_id field is required.',
            'project_id.integer' => 'The project_id must be an integer.',
            'project_id.exists' => 'The selected project_id does not exist.',
        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }
        $project=Project::find($req->project_id);
        if(!$project){
            return $this->returnError('Failed to get project. the project does not exist',404);
        }
        $tasks = $project->tasks()->get();

        return $this->returnData('tasks',$tasks,'Get the Tasks successfully');

    }
    public function confirm_task(Request $req)
    {
        $rules = [
            'task_id' => 'required|integer|exists:tasks,id',
        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'task_id.required' => 'The task_id field is required.',
            'task_id.integer' => 'The task_id must be an integer.',
            'task_id.exists' => 'The selected task_id does not exist.',
        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }
        $task = Task::find($req->task_id);

        // تحقق إذا كانت المهمة موجودة
        if ($task) {
            // تحديث البيانات باستخدام الدالة update
            $task->update([
                'status' => 'abroved'
            ]);
            return $this->returnSuccess("Task updated successfully!");
        }

        // إذا لم يتم العثور على المهمة، أرجع رسالة خطأ
        return $this->returnError('Task not found',404);
    }
    public function git_project_financier(Request $req)
    {
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
        ];

        // رسائل الخطأ المخصصة
        $messages = [
            'user_id.required' => 'The user_id field is required.',
            'user_id.integer' => 'The user_id must be an integer.',
            'user_id.exists' => 'The selected user_id does not exist.',
        ];

        // إنشاء المُحقق
        $validate = Validator::make($req->all(), $rules, $messages);
        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }

        $user=User::find($req->user_id);
            if(!$user){
                return $this->returnError('Failed to get user. the user does not exist',404);
            }
            // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
            $projects = $user->projects()->get();

            return $this->returnData('projects',$projects,'Get the Projects successfully');


    }



}


