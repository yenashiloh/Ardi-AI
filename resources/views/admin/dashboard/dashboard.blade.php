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

       <!-- DataTables Section -->
        <div class="row">
            <div class="col-12">
                <div class="dataTables_wrapper">
                    <!-- Custom DataTable Controls -->
                    <div class="datatable-controls">
                        <div class="datatable-title">
                            User Management
                        </div>
                        <div class="datatable-actions">
                            <button class="datatable-btn datatable-btn-secondary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="datatable-btn datatable-btn-secondary">
                                <i class="fas fa-upload"></i> Import
                            </button>
                            <button class="datatable-btn datatable-btn-primary">
                                <i class="fas fa-plus"></i> Add User
                            </button>
                        </div>
                    </div>

                <!-- DataTable -->
                <table id="example" class="display table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#USR001</td>
                                <td>John Doe</td>
                                <td>john.doe@example.com</td>
                                <td>Administrator</td>
                                <td><span class="status-indicator status-active">Active</span></td>
                                <td>2025-03-15 14:30</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="table-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>      
                                        <button class="table-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Disable">
                                            <i class="fas fa-ban"></i>
                                        </button>                                  
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')z
</body>
</html>