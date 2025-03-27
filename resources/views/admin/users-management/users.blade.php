<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Users</title>

    
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Users</a>
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
                        <button class="datatable-btn datatable-btn-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload"></i> Import
                        </button>                        
                        <button class="datatable-btn datatable-btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div>
                </div>

                
            <!-- Success Message -->
            <div id="successMessage" class="container mt-3"></div>

             <!-- Success Alert -->
 
            <!-- DataTable -->
            <table id="data-table" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id_number}}</td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <span class="status-indicator {{ $user->status === 'Active' ? 'status-active' : 'status-inactive' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button class="table-action-btn" title="Edit" data-bs-toggle="modal" data-bs-target="#userEditModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-action-btn archive-btn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>    
                                        @if($user->status === 'Active')
                                            <button class="table-action-btn disable-btn" data-id="{{ $user->id_number }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Disable">
                                                <i class="fas fa-ban"></i>
                                            </button> 
                                        @endif

                                        @if($user->status === 'Disabled')
                                        <button class="table-action-btn activate-btn" data-id="{{ $user->id }}" 
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Activate">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        @endif
                                        
                                        <button class="table-action-btn view-user-btn" 
                                            data-id-number="{{ $user->id_number }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#userDetailsModal" 
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>                                 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Error Messages Inside Modal -->
                    <div id="errorMessages"></div>

                    <form id="addUserForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <strong class="user-detail-label">First Name</strong>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <strong class="user-detail-label">Last Name</strong>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <strong class="user-detail-label">Email</strong>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contact_number" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <strong class="user-detail-label">Id Number</strong>
                                <input type="text" class="form-control" id="idNumber" name="id_number" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <strong class="user-detail-label">Role</strong>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Collaborators">Collaborators</option>
                                    <option value="Team Leader">Team Leader</option>
                                    <option value="Non-Billable">Non-Billable</option>
                                    <option value="Billable">Billable</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"> Import File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <strong class="user-detail-label">Upload File</strong>
                            <input type="file" class="form-control" id="importFile" required>
                            <small class="text-muted">Allowed formats: .csv, .xls, .xlsx</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="userDetailsModalBody">
                    <!-- User details -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Edit Modal -->
    <div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userEditModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userEditForm">
                        <input type="hidden" id="edit-id-number" name="id_number">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit-first-name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-last-name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit-role" class="form-label">Role</label>
                            <select class="form-select" id="edit-role" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Collaborators">Collaborators</option>
                                <option value="Team Leader">Team Leader</option>
                                <option value="Non-Billable">Non-Billable</option>
                                <option value="Billable">Billable</option>
                            </select>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    @include('admin.partials.footer')
    <script src="../../assets/js/admin/users.js"></script>
</body>
</html>