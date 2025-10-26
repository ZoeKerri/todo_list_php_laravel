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

Route::get('/statistics', function () {
    return view('todo.statistics');
});

// Trang Group chính (Giao diện 1)
Route::get('/group', function () {
    return view('todo.group.group');
});

// Trang chi tiết nhóm (Giao diện 2)
// {id} là một biến, ví dụ: /group-detail/1
Route::get('/group-detail/{id}', function ($id) {
    // Tạm thời, chúng ta sẽ bỏ qua $id và chỉ hiển thị view
    return view('todo.group.group_details');
});

// Trang cài đặt nhóm (Giao diện 3)
// ví dụ: /group-detail/1/settings
Route::get('/group-detail/{id}/settings', function ($id) {
    return view('todo.group.group_settings');
});