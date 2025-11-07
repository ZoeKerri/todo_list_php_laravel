<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@php
    $isLightTheme = App\Helpers\ThemeHelper::isLightTheme();
    $authThemeVars = App\Helpers\ThemeHelper::getAuthThemeColors($isLightTheme);
@endphp

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
            background-color: var(--auth-border);
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
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
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
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }

        .alert-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .alert-info {
            background-color: #17a2b8;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>

    @include('helpers.theme')

    <script>
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
        });
    </script>
</body>
</html>