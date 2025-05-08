<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    <title>Documents</title>
</head>

<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{ route('admin.dashboard.dashboard') }}" class="breadcrumb-link">Dashboard</a>
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
                        Document Management
                    </div>
                    <div class="datatable-actions">
                        <button class="datatable-btn datatable-btn-secondary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="datatable-btn datatable-btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addDocumentModal">
                            <i class="fas fa-plus"></i> Add Documents
                        </button>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success mt-2" id="successMessage">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger mt-2">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Data Table -->
                <table id="data-table" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Document Type</th>
                            <th>Note</th>
                            <th>Uploaded Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td>{{ $document->document_type }}</td>
                                <td>{{ $document->notes }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $document->file_path) }}"
                                        target="_blank">{{ $document->original_file_name }}</a>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button class="table-action-btn" title="Edit" data-bs-toggle="modal"
                                            data-bs-target="#documentEditModal" data-id="{{ $document->id }}"
                                            data-type="{{ $document->document_type }}"
                                            data-notes="{{ $document->notes }}"
                                            data-file-path="{{ $document->file_path }}"
                                            data-file-name="{{ $document->original_file_name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-action-btn" title="Delete" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $document->id }}">
                                            <i class="fas fa-trash"></i>
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

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentModalLabel">Add New Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDocumentForm" action="{{ route('documents.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Document Type -->
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Document Type</label>
                            <input type="text" class="form-control" id="documentType" name="document_type"
                                placeholder="Enter document type" required>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Enter notes..."></textarea>
                        </div>

                        <!-- Upload Document -->
                        <div class="mb-3">
                            <label for="uploadDocument" class="form-label">Upload Document</label>
                            <input type="file" class="form-control" id="uploadDocument" name="uploadDocument"
                                accept=".pdf" required>
                            <div id="fileErrorMessage" class="mt-2"></div> <!-- Error Message -->
                        </div>

                        <!-- Submit Button -->
                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this document?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div class="modal fade" id="documentEditModal" tabindex="-1" aria-labelledby="documentEditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentEditModalLabel">Edit Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-document-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Hidden input for document ID -->
                        <input type="hidden" id="edit-document-id" name="document_id">

                        <!-- Document Type -->
                        <div class="mb-3">
                            <label for="edit-document-type" class="form-label">Document Type</label>
                            <input type="text" class="form-control" id="edit-document-type" name="document_type"
                                required>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="edit-notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit-notes" name="notes" rows="3"></textarea>
                        </div>

                        <!-- Current File Display -->
                        <div class="mb-3">
                            <label class="form-label">Current File</label>
                            <div id="current-file-display">
                                <a href="" id="current-file-link" target="_blank"></a>
                            </div>
                        </div>

                        <!-- New File Upload (optional) -->
                        <div class="mb-3">
                            <label for="edit-file" class="form-label">Replace File (Optional)</label>
                            <input type="file" class="form-control" id="edit-file" name="file">
                            <div class="form-text">Leave empty to keep the current file. </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('admin.partials.footer')
    <script src="../../assets/js/admin/document.js"></script>
</body>

</html>
