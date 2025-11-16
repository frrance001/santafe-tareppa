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
        cursor: pointer;
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

    .driver-photo {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #cbd5e1;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .photo-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .photo-label {
        font-weight: 600;
        color: #0ea5e9;
        margin-top: 10px;
        display: block;
    }

    .photo-card {
        text-align: center;
    }

    .user-actions {
        margin-top: 15px;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-5 glass-container">
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
                        <th>City</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roleUsers as $user)
                        <tr data-user='@json($user)'>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                <form action="/admin/users/{{ $user->id }}/approve" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="/admin/users/{{ $user->id }}/disapprove" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Disapprove</button>
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

    <!-- Print / Download Button -->
    <div class="user-actions">
        <button type="button" class="btn btn-primary btn-sm" id="printAllUsers">Print / Download All</button>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Search filter
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("keyup", () => {
        const filter = searchInput.value.toLowerCase();
        document.querySelectorAll(".user-table tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });

    // Print / Download All Users including images
    document.getElementById('printAllUsers').addEventListener('click', () => {
        const allRows = document.querySelectorAll(".user-table tbody tr");
        if (allRows.length === 0) return alert('No users to print.');

        let htmlContent = `<html>
            <head>
                <title>All Users</title>
                <style>
                    body { font-family: 'Inter', sans-serif; padding: 20px; background: #f8fafc; color: #1f2937; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
                    th { background: #38bdf8; color: #fff; }
                    img { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; margin-right: 10px; }
                    .photo-gallery { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; }
                    .photo-card { text-align: center; }
                    .photo-label { font-weight: 600; color: #0ea5e9; margin-top: 5px; }
                </style>
            </head>
            <body>
                <h2>All Users</h2>`;

        allRows.forEach(row => {
            const user = JSON.parse(row.getAttribute('data-user'));
            const defaultImg = '/images/no-image.png';
            htmlContent += `
                <div style="margin-bottom: 30px;">
                    <ul>
                        <li><strong>ID:</strong> ${user.id}</li>
                        <li><strong>Full Name:</strong> ${user.fullname}</li>
                        <li><strong>Email:</strong> ${user.email}</li>
                        <li><strong>Phone:</strong> ${user.phone}</li>
                        <li><strong>Age:</strong> ${user.age ?? 'N/A'}</li>
                        <li><strong>Sex:</strong> ${user.sex ?? 'N/A'}</li>
                        <li><strong>City:</strong> ${user.city ?? 'N/A'}</li>
                        <li><strong>Role:</strong> ${user.role ?? 'N/A'}</li>
                    </ul>
                    <div class="photo-gallery">
                        <div class="photo-card">
                            <p class="photo-label">Profile Photo</p>
                            <img src="${user.photo ?? defaultImg}" alt="Profile Photo">
                        </div>
                        <div class="photo-card">
                            <p class="photo-label">Police Clearance</p>
                            <img src="${user.police_clearance ?? defaultImg}" alt="Police Clearance">
                        </div>
                        <div class="photo-card">
                            <p class="photo-label">Barangay Clearance</p>
                            <img src="${user.barangay_clearance ?? defaultImg}" alt="Barangay Clearance">
                        </div>
                        <div class="photo-card">
                            <p class="photo-label">Business Permit</p>
                            <img src="${user.business_permit ?? defaultImg}" alt="Business Permit">
                        </div>
                    </div>
                </div>
                <hr>`;
        });

        htmlContent += `</body></html>`;

        const printWindow = window.open('', '_blank', 'width=900,height=700');
        printWindow.document.write(htmlContent);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });
});
</script>
@endsection
