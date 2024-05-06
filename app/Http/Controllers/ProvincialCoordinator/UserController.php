<?php

namespace App\Http\Controllers\ProvincialCoordinator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{    use GeneralTrait;

    public function getRole(Request $request)
    {
        $roles = [
            [
                'name'=>'Provincial',
                'role_number'=>0,
            ],
            [
                'name'=>'LocalCoordinator',
                'role_number'=>1,
            ],
            [
                'name'=>'FinancialManagement',
                'role_number'=>2,
            ],
            [
                'name'=>'Employ',
                'role_number'=>3,
            ],
            [
                'name'=>'supplier',
                'role_number'=>4,
            ]
        ];

        return $this->returnData('roles', $roles,'get role successfully');


    }

    public function createUser(Request $request)
    {
        try {
            //Validated
            $validate = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'role_number' => 'required',
                    'gender' => 'required',
                ]);

            if($validate->fails()){
                $errors = [ 'errorsValidator' => $validate->errors()];
                return $this->returnErrorValidate($errors);

            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_number' => $request->role_number,
                'gender' => $request->gender,
                'center_id' => $request->center_id,
            ]);


            return $this->returnSuccess("Creat user  Successfully");


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }
    public function updateRolById(Request $request)
    {
        try {
            //Validated
            $validate = Validator::make($request->all(),
                [
                    'id' =>'required',
                    'role_number' =>'required',
                ]);

            if($validate->fails()){
                $errors = [ 'errorsValidator' => $validate->errors()];
                return $this->returnErrorValidate($errors);

            }
            $user=User::find($request->id);
            if(!$user){
                return $this->returnError('Failed to updated the user does not exist',404);
            }

            $user->update([
                'role_number' => $request->role_number,
            ]);

            return $this->returnSuccess("User updated Successfully");


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }

    public function getUsersByRole(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(),
            [
                'role_number' => 'required',
            ]);

        if($validate->fails()){
            $errors = [ 'errorsValidator' => $validate->errors()];
            return $this->returnErrorValidate($errors);

        }
        try {


            $users = User::where('role_number', $request->role_number)->get();


            return $this->returnData('users',$users,"get Users  Successfully");


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }

    public function getAllUsers(Request $request)
    {

        try {

            $users = User::get();
            return $this->returnData('users',$users,"get Users  Successfully");

        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }
    }

    public function deleteUser(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        try {

            $user=User::find($request->id);
            if(!$user){
                return $this->returnError('Failed to deleted the user does not exist',404);
            }

            $user->delete();

            return $this->returnSuccess("Country deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the user. Try again after some time');
        }
    }



}
