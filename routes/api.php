<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\PersonalTaskController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TeamMemberController;
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
        Route::post('login-google', [AuthController::class, 'loginGoogle']);
        Route::post('retry-password', [AuthController::class, 'forgotPassword']);
        Route::post('change-password-retry', [AuthController::class, 'resetPassword']);
        Route::post('check-code', [AuthController::class, 'verifyOtp']);
        Route::post('resend-code', [AuthController::class, 'resendCode']);

        // Protected authentication routes
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('profile', [AuthController::class, 'profile']);
        });
    });

    // Categories routes (public)
    Route::middleware('auth:api')->group(function () {
        Route::get('category', [CategoryController::class, 'index']);
        Route::post('category', [CategoryController::class, 'store']);
        Route::delete('category/{category}', [CategoryController::class, 'destroy']);
    });

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

        // Team routes (matching Flutter app)
        Route::prefix('team')->group(function () {
            Route::get('/{userId}', [TeamController::class, 'getTeamsByUserId']);
            Route::get('/detail/{team}', [TeamController::class, 'show']);
            Route::post('/{userId}', [TeamController::class, 'store']); // userId is not used, but matches path
            Route::put('/', [TeamController::class, 'update']);
            Route::delete('/{team}', [TeamController::class, 'destroy']);
            Route::delete('/task/{team}', [TeamController::class, 'destroy']); // Assumes this means delete team and tasks
        });

        // Team Member routes (matching Flutter app)
        Route::prefix('member')->group(function () {
            Route::get('/by-team/{team}', [TeamMemberController::class, 'index']);
            Route::get('/{team}/{user}', [TeamMemberController::class, 'show']); // Get member by team and user ID
            Route::post('/', [TeamMemberController::class, 'store']);
            Route::put('/', [TeamMemberController::class, 'update']);
            Route::delete('/{memberId}', [TeamMemberController::class, 'destroy']);
            Route::delete('/tasks/{teamId}/{userId}', [TeamMemberController::class, 'deleteMemberAndTasks']);
        });

        // Team Task routes (matching Flutter app)
        Route::prefix('team-task')->group(function () {
            Route::get('/by-team/{team}', [TeamTaskController::class, 'index']);
            Route::get('/by-user/{user}', [TeamTaskController::class, 'getTasksForUser']);
            Route::get('/by-member/{member}', [TeamTaskController::class, 'getTasksByMember']);
            Route::post('/', [TeamTaskController::class, 'store']);
            Route::put('/', [TeamTaskController::class, 'update']);
            Route::delete('/{task}', [TeamTaskController::class, 'destroy']);
        });

        // User routes - Đặt route cụ thể TRƯỚC prefix để tránh conflict
        Route::get('user/search', [TeamController::class, 'searchUsersByEmailPrefix']); // Search by prefix
        Route::get('user/by-email/{email}', [TeamController::class, 'getUserByEmail']); // Exact match
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
