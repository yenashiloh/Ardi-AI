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
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item">
            <a href="{{route ('admin.content-management.documents')}}" class="breadcrumb-link">Documents</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Edit Query</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <h3>Edit Query</h3>
                <form action="#" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="question">Enter Question</label>
                        <textarea id="question" name="question" class="form-control" placeholder="Enter your question" rows="3" required>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="response">Enter Response</label>
                        <textarea id="response" name="response" class="form-control" placeholder="Enter your response" rows="10" required>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    @include('admin.partials.footer')
</body>
</html>