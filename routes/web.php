<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp');
Route::post('/otp', [AuthController::class, 'verifyOtp']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/authenticate', [AuthController::class, 'authenticateFromToken'])->name('auth.authenticate');

Route::get('/settings', [SettingsController::class, 'get']);
Route::post('/settings', [SettingsController::class, 'update']);

Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/statistics/monthly-json', [StatisticsController::class, 'monthly'])->name('statistics.monthly');

    Route::get('/group', function () {
        return view('todo.group.group');
    });

    Route::get('/group/{id}', function ($id) {
        return view('todo.group.group_details', ['id' => $id]);
    });
    
    Route::get('/group/{id}/members', function ($id) {
        return view('todo.group.group_listMember', ['id' => $id]);
    });
    
    Route::get('/group/{id}/share', function ($id) {
        return view('todo.group.group_share', ['id' => $id]);
    });
    
    Route::get('/group/{id}/summary', function ($id) {
        return view('todo.group.group_summary', ['id' => $id]);
    });
    
    Route::get('/group/{teamId}/task/{taskId}', function ($teamId, $taskId) {
        return view('todo.group.group_task_detail', ['teamId' => $teamId, 'taskId' => $taskId]);
    });

    Route::get('/account-info', [AccountController::class, 'index']);
    Route::post('/account-info/edit', [AccountController::class, 'update']);
    Route::post('/account-info/change-password', [AccountController::class, 'updatePassword']);
    Route::post('/account-info/upload-avatar', [AccountController::class, 'uploadAvatar']);
    
    Route::get('/api-token', [AuthController::class, 'getApiToken']);
    
});