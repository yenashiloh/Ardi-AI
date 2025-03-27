<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ardi AI Account Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            color: #333333;
        }
        .email-container {
            border: 1px solid #dddddd;
            border-radius: 5px;
            overflow: hidden;
            margin: 20px auto;
        }
        .header {
            background-color: #c9102f;
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .content {
            padding: 25px;
            background-color: #ffffff;
        }
        .credentials-box {
            background-color: #f7f7f7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #eeeeee;
        }
        .credentials-line {
            margin: 10px 0;
        }
        .credentials-label {
            font-weight: bold;
            display: inline;
            color: #555555;
        }
        .login-button {
            display: inline-block;
            background-color: #c9102f;
            color: white !important;
            padding: 10px 20px; 
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin: 10px 0; 
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .login-button:hover {
            background-color: #a80d29;
        }
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
            font-size: 14px;
            color: #666666;
        }
        .automated-message {
            color: #777777;
            font-size: 13px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 3px;
            margin-top: 25px;
        }
        .system-name {
            font-weight: bold;
            color: #c9102f;
        }
        @media only screen and (max-width: 600px) {
            body {
                margin: 10px;
            }
            .header {
                padding: 15px;
                font-size: 20px;
            }
            .content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            Ardi AI Account Details
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->first_name }}</strong>,</p>
            
            <p>Your Ardi AI account has been successfully created! Below are your login credentials:</p>
            
            <div class="credentials-box">
                <p class="credentials-line"><span class="credentials-label">Email:</span> {{ $user->email }}</p>
                <p class="credentials-line"><span class="credentials-label">Password:</span> {{ $password }}</p>
            </div>
            
            <p>For your security, please change your password after your first login.</p>
            
            <div style="text-align: center;">
                <a href="https://ardiai.com/login" class="login-button" style="color: white !important;">Log In Now</a>
            </div>
            
            <p class="automated-message">This is an automated message from the Ardi AI system. Please do not reply to this email.</p>
        </div>
        
        <div class="footer">
            <p>Best regards,<br>
                Admin Team</p>
            <p>© 2025 Ardent Paralegal and Business Solutions Inc. All rights reserved.</p>
        </div>
    </div>
</body>
</html>