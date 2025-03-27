<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.header')
    <title>Add Query</title>

    
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{route ('admin.dashboard.dashboard')}}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item">
            <a href="{{route ('admin.content-management.response')}}" class="breadcrumb-link">Response</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Add Query</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <h3>Add Query</h3>
                <form action="#" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="question">Enter Question</label>
                        <textarea id="question" name="question" class="form-control" placeholder="Enter your question" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="response">Enter Response</label>
                        <textarea id="response" name="response" class="form-control" placeholder="Enter your response" rows="10" required></textarea>
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