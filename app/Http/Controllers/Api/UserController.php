<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class UserController extends Controller
{

    public function __construct() {
        $this -> middleware("auth:api", ['except' => ['register', 'login']]);
    }

    public function register(Request $request) {
        try {
            $validateUser = Validator::make($request ->all(), 
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6'
            ]);

            if($validateUser -> fails()) {
                return response() -> json([
                    'status' => 'error',
                    'message' => 'validation error',
                    'errors' => $validateUser -> errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request -> name,
                'email' => $request -> email,
                'password' => Hash::make($request -> password)
            ]);

            $token = Auth::login($user);

            return response() -> json([
                'status' => 'success',
                'message' => 'User Created Successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ], 200);
        }
        catch(\Throwable $error) {
            return response() -> json([
                'status' => 'error',
                'message' => $error -> getMessage(),
            ], 500);
        } 
    }

    public function login(Request $request) {
        try {
            $validateUser = Validator::make($request ->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if($validateUser -> fails()) {
                return response() -> json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validateUser -> errors()
                ], 401);
            }

            $credentials = $request -> only('email', 'password');

            $token = Auth::attempt($credentials);
            if(!$token) {
                return response() -> json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();

            return response() -> json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ]);
        }
        catch(\Throwable $error) {
            return response() -> json([
                'status' => 'error',
                'message' => $error -> getMessage(),
            ], 500);
        }
    }

    public function getUserDetails() {
        $user = Auth::user();

        if(!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        return response() -> json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function validateToken() {
        $user = Auth::user();
        if(!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json(['user' => $user], 200);
    }

    public function logout() {
        Auth::logout();
        return response() -> json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh() {
        return response() -> json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
