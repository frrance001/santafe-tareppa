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

    /* Modal Glass Style */
    .modal-content {
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(14px);
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="alert alert-warning text-center">No users found.</div>
    @endforelse
</div>

<!-- Modal -->
<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(90deg,#38bdf8,#0ea5e9); color: #fff;">
        <h5 class="modal-title" id="userInfoModalLabel">Driver Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <ul class="list-group mb-3">
          <li class="list-group-item"><strong>ID:</strong> <span id="modal-id"></span></li>
          <li class="list-group-item"><strong>Full Name:</strong> <span id="modal-fullname"></span></li>
          <li class="list-group-item"><strong>Email:</strong> <span id="modal-email"></span></li>
          <li class="list-group-item"><strong>Phone:</strong> <span id="modal-phone"></span></li>
          <li class="list-group-item"><strong>Age:</strong> <span id="modal-age"></span></li>
          <li class="list-group-item"><strong>Sex:</strong> <span id="modal-sex"></span></li>
          <li class="list-group-item"><strong>City:</strong> <span id="modal-city"></span></li>
          <li class="list-group-item"><strong>Role:</strong> <span id="modal-role"></span></li>
        </ul>

        <h6 class="fw-bold text-primary mb-2">Documents & Photos:</h6>
        <div id="photoGallery" class="photo-gallery">
            <div class="photo-card">
                <p class="photo-label">Profile Photo</p>
                <img id="profilePhoto" class="driver-photo" src="" alt="Profile Photo">
            </div>
            <div class="photo-card">
                <p class="photo-label">Police Clearance</p>
                <img id="policeClearance" class="driver-photo" src="" alt="Police Clearance">
            </div>
            <div class="photo-card">
                <p class="photo-label">Barangay Clearance</p>
                <img id="brgyClearance" class="driver-photo" src="" alt="Barangay Clearance">
            </div>
            <div class="photo-card">
                <p class="photo-label">Business Permit</p>
                <img id="businessPermit" class="driver-photo" src="" alt="Business Permit">
            </div>
        </div>
      </div>

      <div class="modal-footer justify-content-between">
        <div>
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
        </div>

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('userInfoModal'));

    document.querySelectorAll(".user-table tbody tr").forEach(row => {
        row.addEventListener("click", e => {
            const user = JSON.parse(row.getAttribute("data-user"));

            // Fill user info
            document.getElementById("modal-id").innerText = user.id;
            document.getElementById("modal-fullname").innerText = user.fullname;
            document.getElementById("modal-email").innerText = user.email;
            document.getElementById("modal-phone").innerText = user.phone;
            document.getElementById("modal-age").innerText = user.age ?? 'N/A';
            document.getElementById("modal-sex").innerText = user.sex ?? 'N/A';
            document.getElementById("modal-city").innerText = user.city ?? 'N/A';
            document.getElementById("modal-role").innerText = user.role ?? 'N/A';

            // Photos
            const defaultImg = '/images/no-image.png';
            document.getElementById('profilePhoto').src = user.photo ?? defaultImg;
            document.getElementById('policeClearance').src = user.police_clearance ?? defaultImg;
            document.getElementById('brgyClearance').src = user.barangay_clearance ?? defaultImg;
            document.getElementById('businessPermit').src = user.business_permit ?? defaultImg;

            // Update form actions
            document.getElementById('approveForm').action = `/admin/users/${user.id}/approve`;
            document.getElementById('disapproveForm').action = `/admin/users/${user.id}/disapprove`;
            document.getElementById('deleteForm').action = `/admin/users/${user.id}`;

            modal.show();
        });
    });

    document.getElementById('deleteForm').addEventListener('submit', e => {
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
