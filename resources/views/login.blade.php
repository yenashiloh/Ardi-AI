<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQmTcoYHC6sLkCO2DO9u8rSTz5tkBvXzXdI+RlA/+Sm2Xq5e3sFktBhXO" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/login.css">
  <style>
 
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
          <h2>Log in</h2>
          <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <!-- Display Error Message -->
            @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        
            <div class="input-group">
                <span class="input-icon">
                    <i class='bx bx-envelope'></i>
                </span>
                <input type="email" name="email" placeholder="Email" required>
            </div>
        
            <div class="input-group">
                <span class="input-icon">
                    <i class='bx bx-lock-alt'></i>
                </span>
                <input type="password" name="password" id="passwordField" placeholder="Password" required>
                <span class="password-toggle" id="togglePassword">
                    <i class='bx bx-hide'></i>
                </span>
            </div>
        
            <div class="form-bottom">
                <div class="form-checkbox">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot Password</a>
            </div>
        
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        
        </div>
      </div>
    </div>
    <script src="assets/js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12PZAgWUNOtTlD8HACkfFhL6M6d7yPjGbSzpDw3g5pX79lnY" crossorigin="anonymous"></script>

    <script></script>
</body>
</html>