<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Dashboard</title>
</head>
<body>
    @include('admin.partials.sidebar')
        <!-- Header -->
        <div class="header">
            <div class="greeting">
                <h1>Hello, Shiloh</h1>
                <p>Dashboard Summary</p>
            </div>
            <div class="header-controls">
                <div class="dropdown">
                    <button class="header-button primary-button dropdown-toggle">
                        Filter By
                    </button>
                </div>
                <button class="header-button">
                    <i class="fas fa-download"></i>
                </button>
                <button class="header-button primary-button">
                    <i class="fas fa-share"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6 animated-card">
                <div class="stat-card pulse-animation">
                    <div class="icon blue">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="title"> Users</div>
                    <div class="value blue">12,432</div>
                    <div class="change positive">
                        <i class="fas fa-arrow-up"></i> 2.5% This Month
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 animated-card">
                <div class="stat-card pulse-animation">
                    <div class="icon purple">
                        <i class="fas fa-users-cog"></i> 
                    </div>
                    <div class="title"> Active Users</div>
                    <div class="value purple">4,132</div>
                    <div class="change positive">
                        <i class="fas fa-arrow-up"></i> 1.5% This Month
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 animated-card">
                <div class="stat-card pulse-animation">
                    <div class="icon green">
                        <i class="fas fa-users"></i> 
                    </div>
                    <div class="title">Daily Active Users</div>                    
                    <div class="value green">100</div>
                    <div class="change negative">
                        <i class="fas fa-arrow-down"></i> 3.4% This Month
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 animated-card">
                <div class="stat-card pulse-animation">
                    <div class="icon pink">
                        <i class="fas fa-comments"></i> 
                    </div>
                    <div class="title">Messages Exchanged</div>                    
                    <div class="value pink">3,532</div>
                    <div class="change negative">
                        <i class="fas fa-arrow-down"></i> 4.5% This Month
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')z
</body>
</html>