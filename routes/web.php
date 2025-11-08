<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks', function () {
    return view('welcome');
});

// Authentication routes
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

// Settings routes (public - for theme settings)
Route::get('/settings', [SettingsController::class, 'get']);
Route::post('/settings', [SettingsController::class, 'update']);

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard and main pages
    Route::get('/statistics', function () {
        return view('todo.statistics');
    });

    // Group pages
    Route::get('/group', function () {
        return view('todo.group.group');
    });

    Route::get('/group-detail/{id}', function ($id) {
        return view('todo.group.group_details');
    });

    Route::get('/group-detail/{id}/settings', function ($id) {
        return view('todo.group.group_settings');
    });

    // Account routes
    Route::get('/account-info', [AccountController::class, 'index']);
    // Route::get('/account-info/edit', [AccountController::class, 'edit']);
    Route::post('/account-info/edit', [AccountController::class, 'update']);
    // Route::get('/account-info/change-password', [AccountController::class, 'changePassword']);
    Route::post('/account-info/change-password', [AccountController::class, 'updatePassword']);
    Route::post('/account-info/upload-avatar', [AccountController::class, 'uploadAvatar']);

    // API token route for frontend
    Route::get('/api-token', [AuthController::class, 'getApiToken']);
});