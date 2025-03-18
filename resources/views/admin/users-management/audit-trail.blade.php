<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Audit Trail</title>

    
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Audit Trail</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="dataTables_wrapper">
                <!-- Custom DataTable Controls -->
                <div class="datatable-controls">
                    <div class="datatable-title">
                        Audit Trail
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
                                <th>Actions</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Ryan Paredes</td>
                                <td>ryanpard@gmail.com</td>
                                <td>Logged out</td>
                                <td>2025-03-15 14:30</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jade Bantilo</td>
                                <td>jadebantilo@gmail.com</td>
                                <td>Logged In</td>
                                <td>2025-03-14 09:45</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Wendy Parallag</td>
                                <td>wendyparallag@gmail.com</td>
                                <td>Sent a message</td>
                                <td>2025-03-10 16:20</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Shiloh Eugenio</td>
                                <td>shiloheugenio@gmail.com</td>
                                <td>Sent a message</td>
                                <td>2025-03-05 11:15</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Paul Derige </td>
                                <td>paullderuge@gmail.com</td>
                                <td>Logged out</td>
                                <td>2025-03-05 11:15</td>
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