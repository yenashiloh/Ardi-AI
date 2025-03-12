<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .otp-box {
            background-color: #940b10;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            border-radius: 5px;
            margin: 20px 0;
            color: #ffffff;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <p>Hi {{ $name }},</p>
        <p>To complete your registration, please use the following OTP code:</p>
        
        <div class="otp-box">{{ $otp }}</div>
        
        <p>This code will expire in 15 minutes.</p>
        <p>If you didn't create an account, you can safely ignore this email.</p>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>