<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TodoController;


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



Route::apiResource("/todo", TodoController::class);

Route::controller(AuthController::class)->group(function () {
    Route::post("login", "login");
    Route::post("register", "register");
    Route::post("logout", "logout");
    Route::post("refresh", "refresh");
    Route::post("me", "me");
});

