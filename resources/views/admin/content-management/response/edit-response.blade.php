<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    <title>Edit Query</title>
</head>

<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{ route('admin.dashboard.dashboard') }}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item">
            <a href="{{ route('admin.content-management.response') }}" class="breadcrumb-link">Response</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Edit Query</a>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="form-card">
                <h3>Edit Query</h3>

                <form action="{{ route('admin.content-management.response.update-response', ['id' => $response->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT') <!-- Indicate it's a PUT request for update -->

                    <div class="form-group">
                        <label for="question">Enter Question</label>
                        <textarea id="question" name="question" class="form-control" placeholder="Enter your question" rows="3" required>{{ $response->question }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="response">Enter Response</label>
                        <textarea id="response" name="response" class="form-control" placeholder="Enter your response" rows="10" required>{{ $response->response }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')
</body>
</html>
