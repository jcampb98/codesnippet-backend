<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request) {
        $request -> validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT 
                            ? response()->json(['status' => __($status), 200]) 
                            : response()->json(['email' => __($status)], 400);
    }

    public function reset(Request $request) {
        $request -> validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $status = Password::reset(
            $request -> only('email', 'password', 'token'),
            function($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET 
                            ? response()->json(['status' => __($status), 200]) 
                            : response()->json(['email' => __($status)], 400);
    }
}
