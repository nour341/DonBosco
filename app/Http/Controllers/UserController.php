<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\NullableType;

class UserController extends Controller
{    use GeneralTrait;

    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
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
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $data = $request->input();
            if(Auth::attempt((['email' => $data['email'], 'password' => $data['password']]))){
                $user = Auth::user();
                if ($user->role_number == null)
                    $user->token = $user->createToken("API TOKEN",['role:provincial'])->plainTextToken;
                elseif ($user->role_number == 1)
                    $user->token = $user->createToken("API TOKEN",['role:local'])->plainTextToken;
                elseif ($user->role_number == 2)
                    $user->token = $user->createToken("API TOKEN",['role:financial'])->plainTextToken;
                elseif ($user->role_number == 3)
                    $user->token = $user->createToken("API TOKEN",['role:employ'])->plainTextToken;

                $data= [
                    'user' => $user
                ];
                return $this->returnData($data,'User Logged In Successfully');
            }
            else
                return $this->returnError('Email & Password does not match with our record.');


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage(),500);

        }

    }


}
