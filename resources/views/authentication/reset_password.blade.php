@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 style="font-size: 2rem;">Reset Password</h1>
    <p class="text-center text-muted" style="margin-top: -20px;">
        Enter your new password
    </p>

    <form action="{{ url('/reset-password') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
        
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" placeholder="New Password" required>
            <i class="fas fa-eye icon-right"></i>
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm New Password" required>
            <i class="fas fa-eye icon-right"></i>
        </div>

        <p class="password-note">
            Password must be more than 6 characters.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding: 0; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>
@endsection

