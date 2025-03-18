<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Create Account</title>

    
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Create Account</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <h3>Create New Account</h3>
                <form action="#" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Enter last name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="tel" id="contactNumber" name="contactNumber" class="form-control" placeholder="Enter contact number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    @include('admin.partials.footer')
</body>
</html>