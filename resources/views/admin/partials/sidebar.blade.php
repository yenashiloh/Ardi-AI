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
        <a href="{{route('admin.dashboard.dashboard')}}" class="sidebar-item {{ request()->routeIs('admin.dashboard.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>

            <span>Dashboard</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Users Management</div>
        <a href="{{route('admin.users.users')}}" class="sidebar-item {{ request()->routeIs('admin.users.users') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>
        <a href="{{route('admin.users-management.audit-trail')}}" class="sidebar-item {{ request()->routeIs('admin.users-management.audit-trail') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i>
            <span>Audit Trail</span>
        </a>
        <a href="{{route('admin.users-management.create-account')}}" class="sidebar-item {{ request()->routeIs('admin.users-management.create-account') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i>
            <span>Create Account</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Content Management</div>
        <a href="{{ route('admin.content-management.documents') }}" 
            class="sidebar-item {{ request()->routeIs('admin.content-management.documents') || request()->routeIs('admin.content-management.documents.add-document') 
            || request()->routeIs('admin.content-management.documents.edit-document')? 'active' : '' }}">
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