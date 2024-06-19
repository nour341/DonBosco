<?php

namespace App\Http\Controllers\LocalCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployController extends Controller
{
    use GeneralTrait;

    public function createEmployee(Request $request)
    {
        try {
            //Validated
            $validate = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'gender' => 'required',
                ]);

            if($validate->fails()){
                return $this->returnErrorValidate($validate->errors());

            }
            $currentUser = Auth::user();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_number' => 3,
                'gender' => $request->gender,
                'center_id' => $currentUser->center_id,
            ]);


            return $this->returnSuccess("Creat user  Successfully");


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }

    public function getAllUsersInCenter()
    {

        try {
            $currentUser = Auth::user();
            $users = User::where('center_id',$currentUser->center_id)->with('center')->get();

            $users->each(function ($user) {
                if ($user->role_number == 3) {
                    $user->user_role = 'Employ';

                }
                elseif ($user->role_number == 2) {
                    $user->user_role = 'Financial Management';

                }
                elseif ( $user->role_number == 1) {
                    $user->user_role = 'Local Coordinator';

                }

                if ($user->center)
                    $user->center_name = $user->center->name;
                else
                    $user->center_name = 'unknown center';
                unset($user->center); // Remove center to clean up the response

            });

            return $this->returnData('users',$users,"get Users  Successfully");

        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }
    public function getEmployeeInCenter()
    {
        try {
            $currentUser = Auth::user();

            // Load users with their associated center
            $users = User::where('center_id', $currentUser->center_id)
                ->where('role_number', 3)
                ->with('center')
                ->get();

            // Modify each user to include the role and center name
            $users->each(function ($user) {
                $user->user_role = 'Employ';
                $user->center_name = $user->center->name;
                unset($user->center); // Remove center to clean up the response

            });

            return $this->returnData('employees', $users, "Get employees successfully");

        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());
        }
    }


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

        #################  Test permission destination ###############################
        // Retrieve the project
        $project = Project::find($req->project_id);
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
        $Employee = User::find($req->user_id);

        if (!$isLocalCoordinator ) {
            return $this->returnError('You do not have permission to add Employee To Project for this project');
        }

        # اذا الموظف من نفس مركز
        if (!$currentUser->center_id == $Employee->center_id ) {
            return $this->returnError('The selected user is from a different center than yours');
        }
        ################# End Test permission destination ###############################


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
        #################  Test permission destination ###############################
        // Retrieve the project
        $project = Project::find($req->project_id);
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;

        if (!$isLocalCoordinator ) {
            return $this->returnError('You do not have permission to remove Employee From Project for this project');
        }
        ################# End Test permission destination ###############################



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

        #################  Test permission destination ###############################

        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->financial_management_id === $currentUser->id;

        if (!$isLocalCoordinator ) {
            return $this->returnError('You do not have permission to Employees Project for this project');
        }

        ################# End Test permission destination ###############################




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
