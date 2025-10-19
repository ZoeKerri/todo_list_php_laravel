<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\PersonalTaskController;
use App\Http\Controllers\Api\TeamTaskController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API v1 routes
Route::prefix('v1')->group(function () {
    
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        
        // Protected authentication routes
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    // Categories routes (public)
    Route::get('category', [CategoryController::class, 'index']);

    // Task count routes (public endpoints as per documentation)
    Route::get('task/count/day/total', [PersonalTaskController::class, 'getTasksCountForDay']);
    Route::get('task/count/day/completed', [PersonalTaskController::class, 'getCompletedTasksCountForDay']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        
        // Personal Tasks routes
        Route::prefix('task')->group(function () {
            Route::get('/', [PersonalTaskController::class, 'index']);
            Route::post('/', [PersonalTaskController::class, 'store']);
            Route::get('{personalTask}', [PersonalTaskController::class, 'show']);
            Route::put('{personalTask}', [PersonalTaskController::class, 'update']);
            Route::delete('{personalTask}', [PersonalTaskController::class, 'destroy']);
        });

        // Team Tasks routes
        Route::prefix('team')->group(function () {
            Route::get('task', [TeamTaskController::class, 'index']);
            Route::post('task', [TeamTaskController::class, 'store']);
            Route::get('task/{teamTask}', [TeamTaskController::class, 'show']);
            Route::put('task/{teamTask}', [TeamTaskController::class, 'update']);
            Route::delete('task/{teamTask}', [TeamTaskController::class, 'destroy']);
        });

        // User routes
        Route::prefix('user')->group(function () {
            Route::get('profile', [UserController::class, 'profile']);
            Route::put('profile', [UserController::class, 'updateProfile']);
            Route::post('avatar', [UserController::class, 'uploadAvatar']);
        });

        // File upload routes
        Route::prefix('file')->group(function () {
            Route::post('upload', [FileController::class, 'upload']);
            Route::get('upload', [FileController::class, 'index']);
            Route::delete('upload/{fileUpload}', [FileController::class, 'destroy']);
        });
    });
});
