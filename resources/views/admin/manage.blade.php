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

    tr.selectable:hover {
        background-color: #e0f2fe;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    tr.selected {
        background-color: #bae6fd !important;
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

    .user-actions {
        margin-top: 15px;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-5 glass-container">
    <input type="text" id="searchInput" class="form-control search-box" placeholder="Search users...">

    {{-- New Drivers --}}
    @if(isset($users['driver']))
        <h3 class="mt-4">New Drivers</h3>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users['driver'] as $user)
                        @if($user->status != 'approved')
                        <tr class="selectable" data-user='@json($user)'>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->status ?? 'pending') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Approved Drivers --}}
    @if(isset($users['driver']))
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users['driver'] as $user)
                        @if($user->status == 'approved')
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->status) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Passengers --}}
    @if(isset($users['passenger']))
        <h3 class="mt-4">Passengers</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- External Buttons --}}
    <div class="user-actions">
        <form id="approveForm" action="" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Approve</button>
        </form>

        <form id="disapproveForm" action="" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">Disapprove</button>
        </form>

        <form id="deleteForm" action="" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>

        <button type="button" class="btn btn-primary btn-sm" id="printUser">Print / Download</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let selectedUser = null;

    // Selectable user rows (drivers only)
    document.querySelectorAll(".user-table tbody tr.selectable").forEach(row => {
        row.addEventListener("click", () => {
            document.querySelectorAll(".user-table tbody tr").forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');
            selectedUser = JSON.parse(row.getAttribute('data-user'));

            // Update external form actions
            document.getElementById('approveForm').action = `/admin/users/${selectedUser.id}/approve`;
            document.getElementById('disapproveForm').action = `/admin/users/${selectedUser.id}/disapprove`;
            document.getElementById('deleteForm').action = `/admin/users/${selectedUser.id}`;
        });
    });

    // Delete confirmation
    document.getElementById('deleteForm').addEventListener('submit', e => {
        e.preventDefault();
        if (!selectedUser) return;
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

    // Search filter
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("keyup", () => {
        const filter = searchInput.value.toLowerCase();
        document.querySelectorAll(".user-table tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });

    // Print / Download
    document.getElementById('printUser').addEventListener('click', () => {
        if (!selectedUser) return;
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>User Details</title>
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 20px; background: #f8fafc; color: #1f2937; }
                        h2 { color: #0ea5e9; }
                        ul { list-style: none; padding: 0; }
                        li { margin-bottom: 8px; font-size: 1rem; }
                    </style>
                </head>
                <body>
                    <h2>User Details</h2>
                    <ul>
                        <li><strong>ID:</strong> ${selectedUser.id}</li>
                        <li><strong>Full Name:</strong> ${selectedUser.fullname}</li>
                        <li><strong>Email:</strong> ${selectedUser.email}</li>
                        <li><strong>Phone:</strong> ${selectedUser.phone}</li>
                        <li><strong>City:</strong> ${selectedUser.city}</li>
                        <li><strong>Status:</strong> ${selectedUser.status ?? 'pending'}</li>
                    </ul>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });
});
</script>
@endsection
