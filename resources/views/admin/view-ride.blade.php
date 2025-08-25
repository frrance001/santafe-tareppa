@extends('layouts.admin')

@section('content')
<style>
    body {
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
        background-color: rgba(0, 0, 0, 0.75);
        z-index: -1;
    }

    .glass-container {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    h1 {
        font-weight: bold;
        color: #facc15;
    }

    table {
        background-color: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    th {
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
    }

    tr:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .btn-danger:hover {
        opacity: 0.85;
    }

    .alert {
        border-radius: 12px;
    }

    iframe {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5 glass-container">
    <h1 class="mb-4 text-center">🛺 Completed Rides</h1>

    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62878.59934401586!2d123.69296307274998!3d11.195128150419343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab2f3b4b0f5e0f%3A0x6a084a0b5b3bfa36!2sBantayan%20Island%2C%20Cebu!5e0!3m2!1sen!2sph!4v1700000000000!5m2!1sen!2sph"
        width="100%" height="400" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped text-white">
            <thead>
                <tr>
                    <th>Ride ID</th>
                    <th>Driver</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Completed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rides as $ride)
                    <tr>
                        <td>{{ $ride->id }}</td>
                        <td>{{ $ride->driver->fullname ?? 'N/A' }}</td>
                        <td>{{ $ride->pickup_location }}</td>
                        <td>{{ $ride->dropoff_location }}</td>
                        <td>{{ $ride->updated_at->format('F j, Y - h:i A') }}</td>
                        <td>
                            <form action="{{ route('admin.completed-rides.destroy', $ride->id) }}" method="POST" class="delete-form d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No completed rides found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This ride record will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });
    });
</script>
@endsection
