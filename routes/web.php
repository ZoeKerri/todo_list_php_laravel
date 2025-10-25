<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Trang Đăng nhập
Route::get('/login', function () {
    return view('authentication.login');
});

// Trang Đăng ký
Route::get('/register', function () {
    return view('authentication.register');
});

// Trang Xác minh OTP
Route::get('/otp', function () {
    return view('authentication.otp');
});