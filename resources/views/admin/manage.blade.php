@extends('layouts.admin')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;
        position: relative;
        color: #000;
    }

    .glass-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h1, h3 { color: #1e3a8a; font-weight: bold; }

    table { background-color: #fff; color: #000; }
    th { background-color: #1e3a8a !important; color: #fff; }
    td { vertical-align: middle; }
    tr:hover { background-color: #f3f4f6; cursor: pointer; }

    .btn { font-weight: bold; border-radius: 8px; }
    .btn-danger { background-color: #ef4444; border: none; color: #fff; }
    .btn-primary { background-color: #3b82f6; border: none; color: #fff; }
    .btn-success { background-color: #10b981; border: none; color: #fff; }
    .btn-warning { background-color: #f59e0b; border: none; color: #fff; }
    .btn:hover { opacity: 0.9; }

    .search-box {
        max-width: 300px;
        margin-bottom: 20px;
    }
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-5 glass-container">
    <!-- Search Box -->
    <input type="text" id="searchInput" class="form-control search-box" placeholder="Search users...">

    @forelse ($users as $role => $roleUsers)
        <h3 class="mt-4">{{ ucfirst($role) }}s</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-striped user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>City</th>
                        <th>Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roleUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->age }}</td>
                            <td>{{ ucfirst($user->sex) }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td class="text-center">
                                <!-- Approve -->
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;" data-action-form>
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>

                                <!-- Disapprove -->
                                <form action="{{ route('admin.users.disapprove', $user->id) }}" method="POST" style="display:inline;" data-action-form>
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-warning">Disapprove</button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" data-delete-form>
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="alert alert-warning text-center">No users found.</div>
    @endforelse
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search filter
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("keyup", function() {
        let filter = searchInput.value.toLowerCase();
        document.querySelectorAll(".user-table tbody tr").forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    // Confirm for approve/disapprove
    const actionForms = document.querySelectorAll('form[data-action-form]');
    actionForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let action = form.querySelector('button').innerText.trim();

            Swal.fire({
                title: `Are you sure you want to ${action}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action}`
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Confirm for delete
    const deleteForms = document.querySelectorAll('form[data-delete-form]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
