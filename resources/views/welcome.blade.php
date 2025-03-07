<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="layout">
            <!-- Logo Section -->
            <div class="logo-section">
                <img src="logo.png" alt="Logo" class="logo">
            </div>
            <!-- Login Form Section -->
            <div class="login-section">
                <div class="login-form">
                    <h2>Log in</h2>
                    <form>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <div class="text-center">
                            <p>or</p>
                            <button type="button" class="btn btn-outline-primary">
                                Continue with Google
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Link to Bootstrap JS and dependencies -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>