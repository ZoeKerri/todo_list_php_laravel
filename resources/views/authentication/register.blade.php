@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="logo-placeholder">
        </div>
    <h1>Register</h1>

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="form-group">
            <i class="fas fa-user"></i>
            <input type="text" name="full_name" class="form-input" placeholder="Full Name" value="{{ old('full_name') }}" required>
        </div>

        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <i class="fas fa-phone"></i>
            <input type="tel" name="phone" class="form-input" placeholder="Phone" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm Password" required>
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

        <button type="submit" class="btn-primary">Register</button>
    </form>

    <p class="text-center text-muted" style="margin-top: 20px;">
        Already have a account? <a href="{{ url('/login') }}" class="link">Login</a>
    </p>
@endsection