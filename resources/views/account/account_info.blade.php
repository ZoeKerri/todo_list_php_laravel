@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="account-container">

    <div class="account-header">
        <a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i></a>
        <h2>Account Settings</h2>
    </div>

    <div class="avatar-section">
        <img src="https://i.imgur.com/g59A6Fp.png" alt="Avatar">
        <a href="#" class="edit-icon">
            <i class="fas fa-camera"></i>
        </a>
    </div>

    <div class="account-form-group">
        <label>Name</label>
        <div class="info-text">
            <i class="fas fa-user"></i>
            Huỳnh Công Tiến
        </div>
    </div>
    
    <div class="account-form-group">
        <label>Email</label>
        <div class="info-text">
            <i class="fas fa-envelope"></i>
            tienhuynh.10904@gmail.com
        </div>
    </div>
    
    <div class="account-form-group">
        <label>Phone</label>
        <div class="info-text">
            <i class="fas fa-phone"></i>
            0966026561
        </div>
    </div>

    <div style="margin-top: 40px;">
        <a href="{{ url('/account-info/edit') }}" class="btn-account btn-primary">
            <i class="fas fa-pen"></i> Update
        </a>
        <a href="{{ url('/account-info/change-password') }}" class="btn-account btn-secondary">
            <i class="fas fa-lock"></i> Change Password
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-account btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

</div>
@endsection