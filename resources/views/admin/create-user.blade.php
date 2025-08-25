@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">➕ Add New User</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'User Created!',
                text: '{{ session("success") }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    <form id="create-user-form" action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" value="{{ old('fullname') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="">Select role</option>
                <option value="Driver">Driver</option>
                <option value="Passenger">Passenger</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" name="age" class="form-control" value="{{ old('age') }}" required>
        </div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select name="sex" class="form-select" required>
                <option value="">Select sex</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const form = document.getElementById('create-user-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Confirm Creation',
            text: 'Are you sure you want to create this user?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, create user!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
