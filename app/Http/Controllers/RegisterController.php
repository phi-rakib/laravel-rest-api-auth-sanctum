<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('LaravelRestAPIAuthSanctum')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User Registered Successfully');
    }

    public function login(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();
            $success['token'] = $user->createToken('LaravelRestAPIAuthSanctum')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User Logged In Successfully');
        } 

        return $this->sendError('Unauthorized', ['error' => 'Unauthorized'], 401);
    }
}
