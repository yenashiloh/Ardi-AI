<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    <title>Archive</title>
</head>

<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{ route('admin.dashboard.dashboard') }}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Archive</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="dataTables_wrapper">
                <!-- Custom DataTable Controls -->
                <div class="datatable-controls">
                    <div class="datatable-title">
                        Archive
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="alert alert-warning mt-3" role="alert">
                   <strong>Note: </strong>Users can only be restored within 30 days of being archived. After 30 days, they will be automatically deleted.
                </div>
                <!-- DataTable -->
                <table id="data-table" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id_number }}</td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <button class="table-action-btn restore-btn" data-bs-toggle="modal"
                                        data-bs-target="#userUnarchiveModal" data-userid="{{ $user->id_number }}">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="userUnarchiveModal" tabindex="-1" aria-labelledby="restoreModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.restore') }}">
                @csrf
                <input type="hidden" name="id_number" id="restoreUserId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to restore this user?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Restore</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-userid');
                document.getElementById('restoreUserId').value = userId;
            });
        });
    </script>
    @include('admin.partials.footer')
</body>

</html>
