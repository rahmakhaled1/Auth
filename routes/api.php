<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(
    [
    'prefix' => "auth"
    ],
    function (){
        Route::controller(AuthController::class)->group(function () {
        Route::post("register","register");
        Route::post("login","login");
        Route::post("logout","logout")->middleware(["auth:sanctum"]);
        Route::post("forgot-password","forgotPassword");
        Route::post("reset-password","resetPassword");
        });
});
