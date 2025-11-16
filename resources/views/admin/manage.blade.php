@extends('layouts.admin')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background: #f0f4f8;
        color: #1f2937;
        font-family: 'Inter', sans-serif;
    }

    .glass-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    h1, h3 {
        background: linear-gradient(90deg, #38bdf8, #0ea5e9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
    }

    table {
        background-color: #fff;
        color: #1f2937;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    th {
        background: linear-gradient(90deg, #38bdf8, #0ea5e9);
        color: #fff !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        vertical-align: middle;
        font-size: 0.95rem;
    }

    tr:hover {
        background-color: #e0f2fe;
        cursor: default;
        transition: background 0.3s ease;
    }

    .btn {
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 16px;
        transition: all 0.3s ease;
        margin-right: 5px;
    }

    .btn-primary {
        background: linear-gradient(90deg, #38bdf8, #0ea5e9);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0ea5e9, #3b82f6);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-success { background-color: #10b981; color: #fff; border: none; }
    .btn-warning { background-color: #f59e0b; color: #fff; border: none; }
    .btn-danger { background-color: #ef4444; color: #fff; border: none; }

    .btn-success:hover,
    .btn-warning:hover,
    .btn-danger:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .search-box {
        max-width: 350px;
        margin-bottom: 25px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-5 glass-container">
    <input type="text" id="searchInput" class="form-control search-box" placeholder="Search users...">

    {{-- New Drivers (Pending Approval) --}}
    @php
        $newDrivers = $users['driver']->where('status', '!=', 'approved');
    @endphp
    @if($newDrivers->count())
        <h3 class="mt-4">New Drivers (Pending Approval)</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($newDrivers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->status ?? 'pending') }}</td>
                            <td>
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.users.disapprove', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">Disapprove</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <form action="{{ route('admin.users.print', $user->id) }}" method="GET" target="_blank" style="display:inline;">
                                    <button class="btn btn-primary btn-sm">Print / Download</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Approved Drivers --}}
    @php
        $approvedDrivers = $users['driver']->where('status', 'approved');
    @endphp
    @if($approvedDrivers->count())
        <h3 class="mt-4">Approved Drivers</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedDrivers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->status) }}</td>
                            <td>
                                <form action="{{ route('admin.users.disapprove', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">Disapprove</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <form action="{{ route('admin.users.print', $user->id) }}" method="GET" target="_blank" style="display:inline;">
                                    <button class="btn btn-primary btn-sm">Print / Download</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Passengers --}}
    @if(isset($users['passenger']) && $users['passenger']->count())
        <h3 class="mt-4">Passengers</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users['passenger'] as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Delete confirmation
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) e.target.submit();
            });
        });
    });

    // Search filter
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("keyup", () => {
        const filter = searchInput.value.toLowerCase();
        document.querySelectorAll(".user-table tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });
});
</script>
@endsection
