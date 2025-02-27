<?php

namespace App\Services\Auth;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "username" =>$data["username"],
            "password" => Hash::make($data["password"]),
        ]);
        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "The user could not be stored."
            ],401);
        }
        $token = $user->createToken('token')->plainTextToken;
        $user["token"] = $token;
        return response()->json([
            "status" => true,
            "message" => "The user has been successfully stored.",
            "data" => new AuthResource($user),
        ]);
    }


    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (!Auth::attempt(["username" =>$data["username"],"password" => $data["password"]])){
            return response()->json([
               "status" => false,
               "message" => "Username Or Password Incorrect."
            ],401);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $user["token"] = $token;
        return response()->json([
            "status" => true,
            "message" => " Welcome Back Again. ",
            "data" => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "Logged out successfully."
        ]);
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $request->validated();

        // إرسال رابط إعادة التعيين باستخدام Password facade
        $status = Password::sendResetLink(['email' => $request->email]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.'])
            : response()->json(['message' => 'Unable to send reset link.'], 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'])
            : response()->json(['message' => 'Password reset failed.'], 400);
    }


}

