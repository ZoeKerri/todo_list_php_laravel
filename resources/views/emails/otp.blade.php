<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #6a1b9a;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .otp-box {
            background: #f8f9fa;
            border: 2px dashed #6a1b9a;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #6a1b9a;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Password Reset</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>You have requested to reset your password. Use the following OTP code to complete the reset process:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <p style="margin: 0; color: #666;">This code will expire in 10 minutes</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong><br>
                If you did not request this password reset, please ignore this email. Your password will remain unchanged.
            </div>
            
            <p>Enter this code on the password reset page to verify your identity.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Your Task App. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

