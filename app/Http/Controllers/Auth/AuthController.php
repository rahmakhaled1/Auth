<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $auth_service){}
    public function register(RegisterRequest $request)
    {
        return $this->auth_service->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->auth_service->login($request);
    }

    public function logout(Request $request)
    {
        return $this->auth_service->logout($request);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        return $this->auth_service->sendResetLink($request);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->auth_service->resetPassword($request);
    }

}
