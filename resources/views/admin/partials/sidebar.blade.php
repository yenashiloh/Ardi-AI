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
        <a href="{{ route('admin.dashboard.dashboard') }}"
            class="sidebar-item {{ request()->routeIs('admin.dashboard.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Users Management</div>
        <a href="{{ route('admin.users.users') }}"
            class="sidebar-item {{ request()->routeIs('admin.users.users') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>
        {{-- <a href="{{ route('admin.users-management.audit-trail') }}"
            class="sidebar-item {{ request()->routeIs('admin.users-management.audit-trail') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i>
            <span>Audit Trail</span>
        </a> --}}
        <a href="{{ route('admin.archive') }}"
            class="sidebar-item {{ request()->routeIs('admin.archive') ? 'active' : '' }}">
            <i class="fas fa-archive"></i>
            <span>Archive</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Content Management</div>
        <a href="{{ route('admin.documents.documents') }}"
            class="sidebar-item {{ request()->routeIs('admin.documents.documents') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Documents</span>
        </a>

        <a href="{{ route('admin.content-management.response') }}"
            class="sidebar-item {{ request()->routeIs('admin.content-management.response') ||
            request()->routeIs('admin.content-management.response.add-response') ||
            request()->routeIs('admin.content-management.response.edit-response')
                ? 'active'
                : '' }}">
            <i class="fas fa-sync"></i>
            <span>Response</span>
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
            <button class="toggle-button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="navbar-icons">
            <div class="dark-mode-toggle" id="darkModeToggle"></div>
            {{-- <button class="icon-button">
                <i class="fas fa-bell"></i>
                <span class="badge">5</span>
            </button> --}}
            <div class="user-dropdown-container">
                <div class="user-dropdown-trigger">
                    <div class="user-avatar-initials">
                        {{ strtoupper(substr($firstName, 0, 1)) }}{{ strtoupper(substr($lastName, 0, 1)) }}</div>
                    <div class="user-info">
                        <span class="user-name">{{ $firstName }}</span>
                        <span class="user-role">{{ $role }}</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>

                <div class="user-dropdown">
                    <a href="{{ route('admin.profile') }}" class="user-dropdown-item">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    <a href="#" class="user-dropdown-item">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                    <div class="user-dropdown-divider"></div>
                    <a href="#" class="user-dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <h6>Are you sure you want to logout?</h6>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
