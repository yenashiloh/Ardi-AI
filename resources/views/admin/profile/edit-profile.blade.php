<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    <title>Profile</title>


</head>

<body>
    @include('admin.partials.sidebar')

    <div class="breadcrumbs">
        <div class="breadcrumb-item">
            <a href="{{ route('admin.dashboard.dashboard') }}" class="breadcrumb-link">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="#" class="breadcrumb-link">Profile</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <h3 class="mb-4">Profile</h3>

                {{-- Display success or error messages --}}
                @if (session('success'))
                    <div class="alert alert-success mt-2">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="passwordForm" action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}" class="form-control"
                                    placeholder="Enter your first name" required tabindex="1">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}" class="form-control"
                                    placeholder="Enter your last name" required tabindex="2">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" class="form-control"
                                    placeholder="Enter your email" required tabindex="3">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Contact Number -->
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number"
                                    value="{{ old('contact_number', $user->contact_number) }}" class="form-control"
                                    placeholder="Enter your contact number" required tabindex="4">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- ID Number -->
                            <div class="form-group">
                                <label for="id_number">ID Number</label>
                                <input type="text" id="id_number" name="id_number"
                                    value="{{ old('id_number', $user->id_number) }}" class="form-control"
                                    placeholder="Enter your ID number" required tabindex="5">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Password -->
                            <div class="form-group position-relative">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter your password" tabindex="6">
                                    <span class="input-group-text toggle-password" data-target="password"
                                        style="cursor: pointer;">
                                        <i class="fa fa-eye-slash" id="togglePasswordIcon1"></i>
                                    </span>
                                </div>
                                <small id="passwordHelp" class="form-text text-danger"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Confirm Password -->
                            <div class="form-group position-relative">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Confirm your password" tabindex="7">
                                    <span class="input-group-text toggle-password" data-target="password_confirmation"
                                        style="cursor: pointer;">
                                        <i class="fa fa-eye-slash" id="togglePasswordIcon2"></i>
                                    </span>
                                </div>
                                <small id="confirmPasswordHelp" class="form-text text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.partials.footer')
    
</body>

</html>
