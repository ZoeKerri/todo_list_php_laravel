@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="logo-placeholder"></div>
    <h1>Forgot Password</h1>

    <p class="text-center text-muted" style="margin-bottom: 30px;">
        Enter your email address and we'll send you an OTP code to reset your password.
    </p>

    <form action="{{ url('/forgot-password') }}" method="POST">
        @csrf
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding: 0; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="btn-primary">Send OTP</button>
    </form>
    
    <p class="text-center" style="margin-top: 20px;">
        <a href="{{ url('/login') }}" class="link">Back to Login</a>
    </p>
@endsection

