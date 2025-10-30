@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="account-container">
    
    <div class="large-icon">
        <i class="fas fa-shield-alt"></i>
    </div>
    
    <h2 style="text-align: center; font-size: 1.5rem; margin-bottom: 30px; color: var(--text-primary);">Change Your Password</h2>

    @if($errors->any())
        <x-alert type="danger" auto-hide="true" hide-delay="7000">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ url('/account-info/change-password') }}" method="POST">
        @csrf
        
        <div class="account-form-group">
            <input type="password" name="old_password" placeholder="Old Password" required>
        </div>
        
        <div class="account-form-group">
            <input type="password" name="new_password" placeholder="New Password" required>
        </div>
        
        <div class="account-form-group">
            <input type="password" name="new_password_confirmation" placeholder="Confirm Password" required>
        </div>

        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 30px;">
            Password must be more than 8 characters, including uppercase, lowercase, numbers and special characters.
        </p>

        <button type="submit" class="btn-account btn-primary">
            Change Password
        </button>
    </form>

</div>
@endsection