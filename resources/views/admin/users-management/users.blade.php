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
    
    <div class="row">
        <div class="col-12">
            <div class="dataTables_wrapper">
                <!-- Custom DataTable Controls -->
                <div class="datatable-controls">
                    <div class="datatable-title">
                        Users
                    </div>
                    <div class="datatable-actions">
                        <button class="datatable-btn datatable-btn-secondary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="datatable-btn datatable-btn-secondary">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
      
                <!-- DataTable -->
                <table id="example" class="display table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Ryan Paredes</td>
                                <td>ryanpard@gmail.com</td>
                                <td><span class="status-indicator status-active">Approved</span></td>
                                <td>2025-03-15 14:30</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-dark">
                                            Disable
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jade Bantilo</td>
                                <td>jadebantilo@gmail.com</td>
                                <td><span class="status-indicator status-active">Approved</span></td>
                                <td>2025-03-14 09:45</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-dark">
                                            Disable
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Wendy Parallag</td>
                                <td>wendyparallag@gmail.com</td>
                                <td><span class="status-indicator status-inactive">Declined</span></td>
                                <td>2025-03-10 16:20</td>
                                <td>
                                    {{-- <div class="table-actions">
                                        <button class="table-action-btn">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-action-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="table-action-btn">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div> --}}
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Shiloh Eugenio</td>
                                <td>shiloheugenio@gmail.com</td>
                                <td><span class="status-indicator status-pending">To Review</span></td>
                                <td>2025-03-05 11:15</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-success">
                                           Edit
                                        </button>
                                        <button class="btn btn-danger">
                                          Archive
                                        </button>
                                        <button class="btn btn-danger">
                                            Disable
                                          </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Paul Derige </td>
                                <td>paullderuge@gmail.com</td>
                                <td><span class="status-indicator status-pending">To Review</span></td>
                                <td>2025-03-16 08:30</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-success">
                                           Approve
                                        </button>
                                        <button class="btn btn-danger">
                                          Decline
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
    @include('admin.partials.footer')
</body>
</html>