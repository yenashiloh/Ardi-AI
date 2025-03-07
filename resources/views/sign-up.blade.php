<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .input-group {
            flex: 1;
            position: relative; 
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            box-sizing: border-box;
        }
        
        .input-group input::placeholder {
            font-size: 14px; 
        }
        
        .password-toggle {
            position: absolute;
            right: 10px; 
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px; 
        }
        
        .input-group input[type="password"] {
            padding-right: 40px; 
        }
    </style>
</head>
<body>
  <div class="login-container">
    <div class="left-side">
      <div class="logo-container">
        <img src="assets/images/Ardi-Logo-White.svg" alt="Ardi Logo" class="logo-img logo-white">
        <img src="assets/images/ardi-text.png" alt="Ardi Text" class="logo-img">
      </div>
    </div>
    <div class="right-side">
      <div class="login-form-container">
        <h2>Create an Account</h2>
        
        <form>
          <div class="form-row">
            <div class="input-group">
              <input type="text" placeholder="First Name" required>
            </div>
            <div class="input-group">
              <input type="text" placeholder="Last Name" required>
            </div>
          </div>
          
          <div class="input-group">
            <input type="email" placeholder="Email" required>
          </div>
          
          <div class="form-row">
            <div class="input-group">
              <input type="password" id="passwordField" placeholder="Password" required>
              <span class="password-toggle" id="togglePassword">
                <i class='bx bx-hide'></i></span>
            </div>
            <div class="input-group">
              <input type="password" id="confirmPasswordField" placeholder="Confirm Password" required>
              <span class="password-toggle" id="toggleConfirmPassword">
                <i class='bx bx-hide'></i></span>
            </div>
          </div>
          
          <div class="form-bottom">
            <div class="form-checkbox">
              <input type="checkbox" id="termsConditions">
              <label for="termsConditions">I agree to the Terms & Conditions</label>
            </div>
          </div>
          
          <button type="submit" class="login-btn">Sign Up</button>
          
          <div class="signup-container">
            Already have an account? <a href="{{route ('login')}}" class="signup-link">Login</a>
          </div>
          
          <div class="divider">
            <span>OR</span>
          </div>
          
          <button type="button" class="google-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
              <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
              <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
              <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
              <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Continue with Google
          </button>
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/sign-up.js"></script>
</body>
</html>