@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="account-container">

    <div class="account-header">
        <a href="{{ url('/account-info') }}"><i class="fas fa-arrow-left"></i></a>
    </div>
    
    <div class="large-icon">
        <i class="fas fa-shield-alt"></i>
    </div>
    
    <h2 style="text-align: center; font-size: 1.5rem; margin-bottom: 30px;">Change Your Password</h2>

    <form action="#" method="POST">
        @csrf
        
        <div class="account-form-group">
            <input type="password" name="old_password" placeholder="Old Password">
        </div>
        
        <div class="account-form-group">
            <input type="password" name="new_password" placeholder="New Password">
        </div>
        
        <div class="account-form-group">
            <input type="password" name="new_password_confirmation" placeholder="Confirm Password">
        </div>
        
        <p style="font-size: 0.8rem; color: #888; margin-bottom: 30px;">
            Password must be more than 8 characters, including uppercase, lowercase, numbers and special characters.
        </p>

        <button type="submit" class="btn-account btn-primary">
            Change Password
        </button>
    </form>

</div>
@endsection