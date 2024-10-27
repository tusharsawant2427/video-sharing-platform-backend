<?php

use App\Features\Users\Http\v1\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/', [AuthController::class, 'show']);
    Route::put('update', [AuthController::class, 'update']);
});
