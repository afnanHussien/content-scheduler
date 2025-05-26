<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlatformController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class,'index']);
    Route::patch('/profile', [ProfileController::class,'update']);
    Route::post('/logout', [AuthController::class,'logout']);

    Route::apiResource('/posts', PostController::class);
    Route::get('/platforms', PlatformController::class);
});
