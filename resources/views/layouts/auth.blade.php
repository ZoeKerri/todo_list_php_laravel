<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Firebase JS SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
@php
    $isLightTheme = App\Helpers\ThemeHelper::isLightTheme();
    $authThemeVars = App\Helpers\ThemeHelper::getAuthThemeColors($isLightTheme);
@endphp

    <!-- Load theme from localStorage immediately to prevent flash -->
    <script>
        (function() {
            // Load theme from localStorage immediately (before page renders)
            const darkMode = localStorage.getItem('dark_mode') !== 'false'; // default to dark mode
            
            // Apply auth theme colors immediately
            const authDarkTheme = {
                '--auth-bg': '#000',
                '--auth-text': '#fff',
                '--auth-text-muted': '#888',
                '--auth-input-bg': '#1e1e1e',
                '--auth-border': '#333',
                '--auth-accent': '#7f00ff',
                '--auth-secondary': '#fff'
            };
            
            const authLightTheme = {
                '--auth-bg': '#ffffff',
                '--auth-text': '#212529',
                '--auth-text-muted': '#6c757d',
                '--auth-input-bg': '#f8f9fa',
                '--auth-border': '#dee2e6',
                '--auth-accent': '#7f00ff',
                '--auth-secondary': '#000'
            };
            
            const colors = darkMode ? authDarkTheme : authLightTheme;
            const root = document.documentElement;
            Object.keys(colors).forEach(key => {
                root.style.setProperty(key, colors[key]);
            });
        })();
    </script>

    <style>
        /* Theme variables for auth pages */
        :root {
            @foreach($authThemeVars as $key => $value)
            {{ $key }}: {{ $value }};
            @endforeach
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--auth-bg);
            color: var(--auth-text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-sizing: border-box;
        }
.logo-placeholder {
    width: 100px;
    height: 100px;
    background-image: url('/storage/logo.jpg'); 
    background-size: cover;  
    background-position: center; 
    border-radius: 50%;
    margin: 0 auto 20px auto;
    transition: background-color 0.3s ease;
}
        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: var(--auth-text);
            transition: color 0.3s ease;
        }
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--auth-text-muted);
            transition: color 0.3s ease;
        }
        .form-group .icon-right {
            left: auto;
            right: 15px;
            cursor: pointer;
        }
        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background-color: var(--auth-input-bg);
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            color: var(--auth-text);
            font-size: 1rem;
            box-sizing: border-box;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .form-input::placeholder {
            color: var(--auth-text-muted);
        }
        .btn-primary {
            width: 100%;
            padding: 15px;
            background-color: var(--auth-accent);
            border: none;
            border-radius: 10px;
            color: var(--auth-text);
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-secondary {
            width: 100%;
            padding: 15px;
            background-color: var(--auth-secondary);
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            color: var(--auth-text);
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        .divider {
            text-align: center;
            color: var(--auth-text-muted);
            margin: 20px 0;
            position: relative;
            transition: color 0.3s ease;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: var(--auth-border);
            transition: background-color 0.3s ease;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        
        .text-center { text-align: center; }
        .text-muted { color: var(--auth-text-muted); }
        .link {
            color: var(--auth-accent);
            text-decoration: none;
            font-weight: bold;
        }
        .password-note {
            font-size: 0.8rem;
            color: var(--auth-text-muted);
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }

        /* CSS cho màn hình OTP */
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 40px 0;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 1.5rem;
            background-color: var(--auth-input-bg);
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            color: var(--auth-text);
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Alert Styles */
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: opacity 0.5s ease;
        }

        .alert-success {
            background-color: #28a745;
            color: var(--auth-text);
        }

        .alert-danger {
            background-color: #dc3545;
            color: var(--auth-text);
        }

        .alert-warning {
            background-color: #ffc107;
            color: var(--auth-text);
        }

        .alert-info {
            background-color: #17a2b8;
            color: var(--auth-text);
        }

        /* Google Login Button Styles */
        .btn-google {
            width: 100%;
            padding: 15px;
            background-color: var(--auth-input-bg);
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            color: var(--auth-text);
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
        }
        .btn-google:hover {
            background-color: var(--auth-bg);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-google img {
            width: 20px;
            height: 20px;
        }
        .btn-google:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>

    @include('helpers.theme')

    <script>
        // Firebase Configuration (từ Flutter app)
        const firebaseConfig = {
            apiKey: "AIzaSyCMyd2sOZhasX0j2HYpTTYc2asxq5aoaKk",
            authDomain: "todolistphp.firebaseapp.com",
            projectId: "todolistphp",
            storageBucket: "todolistphp.firebasestorage.app",
            messagingSenderId: "975281618795",
            appId: "1:975281618795:web:bc6f7db2dc103038b3962e"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        // Load settings on page load for auth pages
        document.addEventListener('DOMContentLoaded', function() {
            window.ThemeHelper.loadAuthTheme();
            
            // Toggle password visibility
            const passwordIcons = document.querySelectorAll('.icon-right');
            passwordIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const passwordInput = this.previousElementSibling;
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });

            // Google Login Handler
            const googleLoginBtn = document.getElementById('googleLoginBtn');
            if (googleLoginBtn) {
                googleLoginBtn.addEventListener('click', handleGoogleLogin);
            }
        });

        // Google Login Function (giống logic Flutter)
        async function handleGoogleLogin() {
            const btn = document.getElementById('googleLoginBtn');
            if (!btn) return;

            try {
                btn.disabled = true;
                btn.textContent = 'Logging in...';

                // Sign out trước (giống Flutter)
                await firebase.auth().signOut();

                // Tạo Google Auth Provider
                const provider = new firebase.auth.GoogleAuthProvider();
                
                // Đăng nhập với Google
                const result = await firebase.auth().signInWithPopup(provider);
                const user = result.user;

                if (!user || !user.email) {
                    throw new Error('Could not get user information from Google');
                }

                // Lấy thông tin người dùng (giống Flutter)
                const email = user.email;
                const displayName = user.displayName || '';
                const photoURL = user.photoURL || '';

                console.log('Successfully logged in with Google:', email, displayName, photoURL);

                // Gửi dữ liệu về Laravel API (giống Flutter)
                const response = await fetch('/api/v1/auth/login-google', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        displayName: displayName,
                        photoURL: photoURL
                    })
                });

                const data = await response.json();
                console.log('Response từ server:', data);

                if (response.ok && data.status === 200) {
                    // Lưu token vào localStorage
                    if (data.data && data.data.accessToken) {
                        localStorage.setItem('access_token', data.data.accessToken);
                        localStorage.setItem('user', JSON.stringify(data.data.user));
                    }

                    // Authenticate vào Laravel session
                    const authResponse = await fetch('/auth/authenticate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            token: data.data.accessToken
                        })
                    });

                    const authData = await authResponse.json();
                    
                    if (authResponse.ok && authData.success) {
                        // Redirect đến trang chính
                        window.location.href = '/statistics';
                    } else {
                        throw new Error('Could not authenticate session');
                    }
                } else {
                    throw new Error(data.message || 'Login failed');
                }
            } catch (error) {
                console.error('Error logging in with Google:', error);
                
                // Show error message
                alert('Error: ' + (error.message || 'Google login failed'));
                
                // Reset button
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="width: 20px; height: 20px;"> Login with Google';
                }
            }
        }
    </script>

    @stack('scripts')

</body>
</html>