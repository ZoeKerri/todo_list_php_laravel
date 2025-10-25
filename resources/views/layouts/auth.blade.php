<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
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
            background-color: #333;
            border-radius: 50%;
            margin: 0 auto 20px auto;
        }
        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
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
            color: #888;
        }
        .form-group .icon-right {
            left: auto;
            right: 15px;
            cursor: pointer;
        }
        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px; /* Thêm padding trái cho icon */
            background-color: #1e1e1e;
            border: 1px solid #333;
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            box-sizing: border-box; /* Đảm bảo padding không làm vỡ layout */
        }
        .form-input::placeholder {
            color: #888;
        }
        .btn-primary {
            width: 100%;
            padding: 15px;
            background-color: #7f00ff; /* Màu tím */
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-secondary {
            width: 100%;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            color: #000;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .divider {
            text-align: center;
            color: #888;
            margin: 20px 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #333;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        
        .text-center { text-align: center; }
        .text-muted { color: #888; }
        .link {
            color: #7f00ff;
            text-decoration: none;
            font-weight: bold;
        }
        .password-note {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 20px;
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
            background-color: #1e1e1e;
            border: 1px solid #333;
            border-radius: 10px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>