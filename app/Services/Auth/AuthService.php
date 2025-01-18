<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;

class AuthService
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
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
}
