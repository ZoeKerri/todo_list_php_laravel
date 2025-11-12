@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="logo-placeholder">
    </div>
    <h1>Login</h1>

    <form id="login-form">
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

        <!-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif -->

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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('login-form');
            if (!loginForm) return;
            loginForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                const emailInput = loginForm.querySelector('input[name="email"]');
                const passwordInput = loginForm.querySelector('input[name="password"]');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const email = emailInput.value;
                const password = passwordInput.value;
                const submitButton = loginForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Logging in...';
                try {
                    const response = await fetch('/api/v1/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            email: email,
                            password: password
                        })
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || data.error || 'Login failed.');
                    }
                    if (data.data && data.data.accessToken) {
                        const accessToken = data.data.accessToken;
                        localStorage.setItem('access_token', accessToken);
                        const authResponse = await fetch('/auth/authenticate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                token: accessToken
                            })
                        });
                        const authData = await authResponse.json();
                        if (authResponse.ok && authData.success) {
                            window.location.href = '/';
                        } else {
                            throw new Error('Failed to create web session.');
                        }
                    } else {
                        throw new Error('Login successful, but no token received.');
                    }
                } catch (error) {
                    console.error('Login Error:', error);
                    alert('Login failed: ' + error.message);
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            });
        });
    </script>
@endpush