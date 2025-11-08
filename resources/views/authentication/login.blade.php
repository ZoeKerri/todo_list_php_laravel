@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="logo-placeholder">
        <img src="{{ asset('images/my-logo.png') }}" alt="MyLogo">
        </div>
    <h1>Login</h1>

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <i class="fas fa-eye icon-right"></i>
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

        <button type="submit" class="btn-primary">Login</button>
    </form>

    <div class="divider">or</div>

    <button type="button" id="googleLoginBtn" class="btn-google">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
        Login with Google
    </button>
    
    <p class="text-center" style="margin-top: 20px;">
        <a href="{{ url('/forgot-password') }}" class="link">Forgot Password?</a>
        <span class="text-muted" style="margin: 0 10px;">|</span>
        <a href="{{ url('/register') }}" class="link">Sign Up</a>
    </p>
@endsection