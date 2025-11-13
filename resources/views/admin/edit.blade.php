@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">✏️ Edit User</h2>

    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    <form id="edit-user-form" action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" name="fullname" value="{{ $user->fullname }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="driver" {{ $user->role == 'Driver' ? 'selected' : '' }}>Driver</option>
                <option value="passenger" {{ $user->role == 'Passenger' ? 'selected' : '' }}>Passenger</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.manage') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection

@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('edit-user-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Confirm Update',
                text: "Are you sure you want to update this user's details?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, update it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
