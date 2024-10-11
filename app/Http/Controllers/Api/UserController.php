<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function createUser(Request $request) {
        try {
            $validateUser = Validator::make($request ->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|unique:users,username',
                'password' => 'required'
            ]);

            if($validateUser -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser -> errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request -> name,
                'email' => $request -> email,
                'username' => $request -> username,
                'password' => bcrypt($request -> password)
            ]);

            return response() -> json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user -> createToken("API TOKEN") -> plainTextToken
            ], 200);
        }
        catch(\Throwable $error) {
            return response() -> json([
                'status' => false,
                'message' => $error -> getMessage(),
            ], 500);
        } 
    }

    public function loginUser(Request $request) {
        try {
            $validateUser = Validator::make($request ->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser -> errors()
                ], 401);
            }

            if(!Auth::attempt($request -> only(['email', 'password']))) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Email & Password does not match.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response() -> json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user -> createToken("API TOKEN") -> plainTextToken
            ], 200);
        }
        catch(\Throwable $error) {
            return response() -> json([
                'status' => false,
                'message' => $error -> getMessage(),
            ], 500);
        }
    }
}
