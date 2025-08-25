@extends('layouts.admin')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background: url('/images/hoii.png') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: -1;
    }

    .glass-container {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    h1, h3 {
        color: #facc15;
        font-weight: bold;
    }

    table {
        background-color: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    th {
        background-color: rgba(0, 0, 0, 0.6) !important;
        color: #fff;
    }

    td {
        vertical-align: middle;
    }

    tr:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .btn {
        font-weight: bold;
        border-radius: 8px;
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<div class="container mt-5 glass-container">
    <h1 class="mb-4 text-center">👥 Manage Users</h1>

    @forelse ($users as $role => $roleUsers)
        <h3 class="mt-4">{{ ucfirst($role) }}s</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
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
                            <td>{{ ucfirst($user->role) }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
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
@endsection
