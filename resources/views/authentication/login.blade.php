@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="logo-placeholder">
        </div>
    <h1>Login</h1>

    <form action="{{-- url('/login') --}}" method="POST">
        @csrf
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-input" placeholder="Email">
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" placeholder="Password">
            <i class="fas fa-eye icon-right"></i> </div>

        <button type="submit" class="btn-primary">Login</button>
    </form>
        <p class="text-center" style="margin-top: 20px;">
        <a href="#" class="link">Forgot Password?</a>
        <span class="text-muted" style="margin: 0 10px;">|</span>
        <a href="{{-- url('/register') --}}" class="link">Sign Up</a>
    </p>
@endsection