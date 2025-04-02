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
            <a href="{{ route('admin.dashboard.dashboard') }}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Response</a>
        </div>
    </div>




    <div class="row">
        <div class="col-12">
            <div class="dataTables_wrapper">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                <table id="data-table" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Question</th>
                            <th>Response</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($responses as $index => $response)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $response->question }}</td>
                                <td>{{ Str::limit($response->response, 100) }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('admin.content-management.response.edit-response', ['id' => $response->id]) }}"
                                            class="table-action-btn">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button class="table-action-btn btn-delete" data-id="{{ $response->id }}"
                                            data-question="{{ $response->question }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
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
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this response?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.footer')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var deleteModal = document.getElementById("deleteModal");
            deleteModal.addEventListener("show.bs.modal", function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var responseId = button.getAttribute("data-id"); // Get response ID from data-id attribute
                var form = document.getElementById("deleteForm");
                form.action = "/admin/content-management/response/" + responseId;
            });
        });
    </script>
</body>

</html>
