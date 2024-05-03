<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{    use GeneralTrait;


    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $validate = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);


            if($validate->fails()){
                return $this->returnErrorValidate($validate->errors());

            }

            $data = $request->input();
            if(Auth::attempt((['email' => $data['email'], 'password' => $data['password']]))){
                $user = Auth::user();
                $user->token = '';
                if ($user->role_number == 0)
                    $user->token = $user->createToken("API TOKEN",['role:provincial'])->plainTextToken;
                elseif ($user->role_number == 1)
                    $user->token = $user->createToken("API TOKEN",['role:local'])->plainTextToken;
                elseif ($user->role_number == 2)
                    $user->token = $user->createToken("API TOKEN",['role:financial'])->plainTextToken;
                elseif ($user->role_number == 3)
                    $user->token = $user->createToken("API TOKEN",['role:employ'])->plainTextToken;


                return $this->returnData('user', $user,'User Logged In Successfully');
            }
            else
                return $this->returnError('Email & Password does not match with our record.',404);


        } catch (\Throwable $th) {
            return $this->returnError($th->getMessage());

        }

    }


}
