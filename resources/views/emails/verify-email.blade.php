<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    <link rel="stylesheet" href="../assets/css/login.css">
    <style>
        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 25px 0;
        }
        
        .otp-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .resend-container {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        
        .resend-link {
            color: #2563eb;
            cursor: pointer;
            text-decoration: none;
        }
        
        .resend-link:hover {
            text-decoration: underline;
        }
        
        .success-message, .error-message {
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            display: none;
            text-align: center;
        }
        
        .success-message {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .error-message {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-side">
            <div class="logo-container">
                <img src="../assets/images/Ardi-Logo-White.svg" alt="Ardi Logo" class="logo-img logo-white">
                <img src="../assets/images/ardi-text.png" alt="Ardi Text" class="logo-img">
            </div>
        </div>
        <div class="right-side">
            <div class="login-form-container">
                <h2>Check your inbox</h2>
                <p>We've sent a verification code to your email. Please enter the code below.</p>
                
                <div id="successMessage" class="success-message"></div>
                <div id="errorMessage" class="error-message"></div>
                
                <form id="otpForm">
                    <div class="otp-inputs">
                        <input type="text" class="otp-input" maxlength="1" data-index="1" autofocus>
                        <input type="text" class="otp-input" maxlength="1" data-index="2">
                        <input type="text" class="otp-input" maxlength="1" data-index="3">
                        <input type="text" class="otp-input" maxlength="1" data-index="4">
                        <input type="text" class="otp-input" maxlength="1" data-index="5">
                        <input type="text" class="otp-input" maxlength="1" data-index="6">
                    </div>
                    
                    <input type="hidden" id="userId" value="">
                    <input type="hidden" id="fullOtp" value="">
                    
                    <button type="submit" class="login-btn">Verify Email</button>
                    
                    <div class="resend-container">
                        Didn't receive a code? <a href="#" class="resend-link" id="resendOtp">Resend OTP</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get user_id from URL params
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user_id');
            
            if (userId) {
                document.getElementById('userId').value = userId;
            } else {
                showError('User ID missing. Please try registering again.');
            }
            
            // OTP input handling
            const otpInputs = document.querySelectorAll('.otp-input');
            
            otpInputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1) {
                        const nextIndex = parseInt(this.getAttribute('data-index')) + 1;
                        const nextInput = document.querySelector(`.otp-input[data-index="${nextIndex}"]`);
                        
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                    
                    // Combine all inputs to form the complete OTP
                    let otp = '';
                    otpInputs.forEach(input => {
                        otp += input.value;
                    });
                    
                    document.getElementById('fullOtp').value = otp;
                });
                
                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && this.value === '') {
                        const prevIndex = parseInt(this.getAttribute('data-index')) - 1;
                        const prevInput = document.querySelector(`.otp-input[data-index="${prevIndex}"]`);
                        
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                });
            });
            
            // OTP form submission
            document.getElementById('otpForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const userId = document.getElementById('userId').value;
                const otp = document.getElementById('fullOtp').value;
                
                if (otp.length !== 6) {
                    showError('Please enter all 6 digits of the OTP code.');
                    return;
                }
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                try {
                    const response = await fetch('/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            otp: otp
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        showSuccess(result.message);
                        // Redirect to login page after 2 seconds
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                    } else {
                        showError(result.message || 'Verification failed. Please try again.');
                    }
                } catch (error) {
                    showError('An error occurred. Please try again.');
                    console.error('Error:', error);
                }
            });
            
            // Resend OTP
            document.getElementById('resendOtp').addEventListener('click', async function(e) {
                e.preventDefault();
                
                const userId = document.getElementById('userId').value;
                
                if (!userId) {
                    showError('User ID missing. Please try registering again.');
                    return;
                }
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                try {
                    const response = await fetch('/resend-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            user_id: userId
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        showSuccess('OTP resent. Please check your email.');
                        
                        // Clear input fields
                        otpInputs.forEach(input => {
                            input.value = '';
                        });
                        document.getElementById('fullOtp').value = '';
                        otpInputs[0].focus();
                    } else {
                        showError(result.message || 'Failed to resend OTP. Please try again.');
                    }
                } catch (error) {
                    showError('An error occurred. Please try again.');
                    console.error('Error:', error);
                }
            });
            
            // Helper functions
            function showSuccess(message) {
                const successElement = document.getElementById('successMessage');
                successElement.textContent = message;
                successElement.style.display = 'block';
                
                // Hide error message if visible
                document.getElementById('errorMessage').style.display = 'none';
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    successElement.style.display = 'none';
                }, 5000);
            }
            
            function showError(message) {
                const errorElement = document.getElementById('errorMessage');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
                
                // Hide success message if visible
                document.getElementById('successMessage').style.display = 'none';
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    errorElement.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>