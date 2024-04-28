<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectUser;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class EmployController extends Controller
{
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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        ProjectUser::create([
            'project_id'=>$req->project_id,
            'user_id'=>$req->user_id,
        ]);
        $arr=[
            'message'=>'The employee has been successfully selected for the project',
            'status'=>200
        ];
        return response($arr,200);

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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        $project=Project::find($req->project_id);
        if ($project) {
            // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
            $users = $project->users()->get();

            $arr = [
                'message' => $users,
                'status' => 200
            ];
        } else {
            // إذا لم يتم العثور على المشروع، يمكنك إرجاع رسالة خطأ
            $arr = [
                'message' => 'Project not found',
                'status' => 404
            ];
        }

        return response($arr, $arr['status']);
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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        Task::create([
            'project_id'=>$req->project_id,
            'user_id'=>$req->user_id,
            'description'=>$req->description,
            'end_date'=>$req->end_date,
            'start_date'=>$req->start_date,
            'status'=>$req->status,
        ]);
        $arr=[
            'message'=>'The task has been successfully selected for the project',
            'status'=>200
        ];
        return response($arr,200);
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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        $project=Project::find($req->project_id);
        if ($project) {
            // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
            $tasks = $project->tasks()->get();

            $arr = [
                'message' => $tasks,
                'status' => 200
            ];
        } else {
            // إذا لم يتم العثور على المشروع، يمكنك إرجاع رسالة خطأ
            $arr = [
                'message' => 'Project not found',
                'status' => 404
            ];
        }

        return response($arr, $arr['status']);
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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        $task = Task::find($req->task_id);

        // تحقق إذا كانت المهمة موجودة
        if ($task) {
            // تحديث البيانات باستخدام الدالة update
            $task->update([
                'status' => 'abroved'
            ]);

            return response()->json([
                'message' => 'Task updated successfully!',

            ], 200);
        }

        // إذا لم يتم العثور على المهمة، أرجع رسالة خطأ
        return response()->json([
            'message' => 'Task not found'
        ], 404);
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
        $validator = Validator::make($req->all(), $rules, $messages);

        // التحقق من البيانات
        if ($validator->fails()) {
            // الاستجابة بالأخطاء إذا فشل التحقق
            return response()->json($validator->errors(), 400);
        }
        $user=User::find($req->user_id);
        if ($user) {
            // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
            $projects = $user->projects()->get();

            $arr = [
                'message' => $projects,
                'status' => 200
            ];
        } else {
            // إذا لم يتم العثور على المشروع، يمكنك إرجاع رسالة خطأ
            $arr = [
                'message' => 'Downer not found',
                'status' => 404
            ];
        }

        return response($arr, $arr['status']);
    }

    public function createEmploy(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'role_number' => 'required',
                    'gender' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_number' => $request->role_number,
                'gender' => $request->gender,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Creat Employed  Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
