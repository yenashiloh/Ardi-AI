<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="icon" href="../../assets/images/Ardi-Logo.svg" type="image/x-icon"/>
</head>
<body>
    <!-- Toggle Button for Mobile -->
    <button class="toggle-button" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <img src="../assets/images/Ardi-Logo.svg" alt="Ardi Logo">
            </div>
            <span> Ardi AI</span>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">MAIN</div>
            <a href="#" class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Users Management</div>
            <a href="#" class="sidebar-item">
                <i class="fas fa-user"></i>
                <span>Users</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-layer-group"></i>
                <span>Audit Trail</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-user-plus"></i>
                <span>Create Account</span>
            </a>
            
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Content Management</div>
            <a href="#" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                <span>Documents</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-newspaper"></i> 
                <span>Content</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Navbar -->
        <div class="navbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search for Results...">
            </div>
            <div class="navbar-icons">
                <div class="dark-mode-toggle" id="darkModeToggle"></div>
                <button class="icon-button">
                    <i class="fas fa-bell"></i>
                    <span class="badge">5</span>
                </button>
                <button class="icon-button">
                    <i class="fas fa-expand"></i>
                </button>
                <img src="../../../assets/images/photo3.jpg" alt="User Avatar" class="user-avatar">
            </div>
        </div>

        <!-- Header -->
        <div class="header">
            <div class="greeting">
                <h1>Hello, User</h1>
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



    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="../../assets/js/admin/main.js"></script>
</body>
</html>