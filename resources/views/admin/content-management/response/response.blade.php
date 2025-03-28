<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Response</title>
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Response</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="dataTables_wrapper">
                <!-- Custom DataTable Controls -->
                <div class="datatable-controls">
                    <a href="{{ route('admin.content-management.response.add-response') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Query
                    </a>
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
                                <th>Question</th>
                                <th>Response</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($responses as $index => $response)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $response->question }}</td>
                                    <td>{{ Str::limit($response->response, 100) }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('admin.content-management.response.edit-response') }}" class="table-action-btn">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="table-action-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="table-action-btn">
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
    </div>
    @include('admin.partials.footer')
</body>
</html>