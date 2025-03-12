<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
     
    .password-toggle {
        position: absolute;
        right: 10px; 
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px; 
        z-index: 1;
        background: transparent;
        border: none;
        color: #666;
    }

    .form-checkbox {
        margin: 20px 0;
        display: flex;
        align-items: center;
    }

    .form-checkbox input[type="checkbox"] {
        margin-right: 10px;
    }

    .back-link {
      display: flex;
      align-items: center;
      color: #333;
      text-decoration: none;
      font-size: 14px;
      margin-bottom: 20px;
      cursor: pointer;
      padding: 10px 0;
      order-bottom: 1px solid #eee;
    }

      .back-link i {
          margin-right: 8px;
      }
      
      .input-group input[type="password"], 
      .input-group input[type="text"] {
          padding-right: 40px; 
      }

      input[type="text"],
      input[type="email"],
      input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 15px;
        transition: border-color 0.3s;
      }

      #step2 {
          display: none;
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
        <div id="step1">
          <h2>Create an Account</h2>
          <form id="emailForm">
            <div class="input-group">
              <input type="email" id="emailInput" placeholder="Email" required>
            </div>
            
            <button type="submit" class="login-btn">Continue</button>
            
            <div class="signup-container">
              Already have an account? <a href="{{route ('login')}}" class="signup-link">Login</a>
            </div>
            
            {{-- <div class="divider">
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
            </button> --}}
          </form>
        </div>
        
        <!-- Step 2-->
        <div id="step2" style="display: none;">
          
          <div class="back-link" id="backButton">
            <i class='bx bx-chevron-left'></i> Back
          </div>
          <h2>Create an Account</h2>

          <form id="fullForm" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="success-message" style="display: none;"></div>
            <div class="error-message" style="display: none;"></div>
            <div class="input-group">
              <input type="text" name="first_name" placeholder="First Name" required>
            </div>
            
            <div class="input-group">
              <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
            
            <div class="input-group">
              <input type="email" id="displayEmail" name="email" readonly>
            </div>
            
            <div class="input-group">
              <input type="password" id="passwordField" name="password" placeholder="Password" required>
              <button type="button" class="password-toggle" id="togglePassword">
                <i class='bx bx-hide'></i>
              </button>
            </div>
            
            <div class="input-group">
              <input type="password" id="confirmPasswordField" name="password_confirmation" placeholder="Confirm Password" required>
              <button type="button" class="password-toggle" id="toggleConfirmPassword">
                <i class='bx bx-hide'></i>
              </button>
            </div>
            
            <div class="form-checkbox">
              <input type="checkbox" id="termsConditions" required>
              <label for="termsConditions">I agree to the Terms & Conditions</label>
            </div>
            
            <button type="submit" class="login-btn">Sign Up</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/sign-up.js"></script>
</body>
</html>