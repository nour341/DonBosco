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

    public function addEmployeeToProject(Request $req)
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
            return $this->returnErrorValidate($validate->errors());

        }

        # التاكد اذا كان مضاف مسبقا
        $projectUser = ProjectUser::where('project_id', $req->project_id)
            ->where('user_id', $req->user_id)
            ->first();

        if ($projectUser) {

            return $this->returnError('Already added', 404);

        }

        try {

            ProjectUser::create([
                'project_id'=>$req->project_id,
                'user_id'=>$req->user_id,
            ]);

            return $this->returnSuccess('The employee has been successfully selected for the project');


        } catch (\Throwable $th) {
            return $this->returnError('Failed Add the  employee. Try again after some time');

        }
    }

    public function removeEmployeeFromProject(Request $req)
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
            return $this->returnErrorValidate($validate->errors());
        }

        try {

            $projectUser = ProjectUser::where('project_id', $req->project_id)
                ->where('user_id', $req->user_id)
                ->first();

            if ($projectUser) {
                // حذف السجل
                $projectUser->delete();

                return $this->returnSuccess('The employee has been successfully removed from the project');
            } else {
                return $this->returnError('The employee is not assigned to the project');
            }
        } catch (\Throwable $th) {
            return $this->returnError('Failed removed the  employee. Try again after some time');
        }
    }

    public function getEmployeesProject(Request $req)
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
            return $this->returnErrorValidate($validate->errors());

        }

        $project=Project::with('users')->find($req->project_id);
        if(!$project){
            return $this->returnError('Failed to get project. the project does not exist',404);
        }
        // تأكد من استدعاء دالة get() للحصول على النتائج الفعلية للمستخدمين
        $users = $project->users()->get();
        // Modify each user to include the role and center name
        $users->each(function ($user) {
            $user->user_role = 'Employ';
            if ($user->center)
            $user->center_name = $user->center->name;
            else
                $user->center_name = 'unknown center';
            unset($user->center); // Remove center to clean up the response
            unset($user->pivot); // Remove pivot to clean up the response

        });
        return $this->returnData('employees',$users,'Get the Tasks successfully');

    }


}


