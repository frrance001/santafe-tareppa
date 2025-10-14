@extends('layouts.admin')

@section('content')
<style>
    body {
        background: #ffffff !important; /* ✅ White background */
        position: relative;
        color: #000; /* ✅ Dark text */
    }

    body::before {
        display: none; /* ✅ Remove dark overlay */
    }

    .glass-container {
        background: #ffffff; /* ✅ Solid white container */
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        color: #000;
    }

    h1 {
        font-weight: bold;
        color: #1e3a8a; /* ✅ Dark blue heading */
    }

    table {
        background-color: #ffffff;
        color: #000;
    }

    th {
        background-color: #f3f4f6; /* ✅ Light gray header */
        color: #000;
    }

    tr:hover {
        background-color: #f9fafb; /* ✅ Light hover effect */
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
        color: #fff;
    }

    .btn-danger:hover {
        opacity: 0.85;
    }

    .alert {
        border-radius: 12px;
    }

    #map {
        height: 400px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Leaflet.js (Free GPS Map) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div class="container mt-5 glass-container">
    <h1 class="mb-4 text-center"> Completed Rides</h1>

    <!-- Replaced Google Maps with GPS Tracker -->
    <div id="map"></div>

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
        <table class="table table-bordered table-striped">
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
    // Initialize the map (centered on Cebu as default)
    var map = L.map('map').setView([11.1951, 123.6929], 13);

    // OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Example: loop through completed rides with GPS (if available)
    @foreach($rides as $ride)
        @if(isset($ride->driver_lat) && isset($ride->driver_lng))
            L.marker([{{ $ride->driver_lat }}, {{ $ride->driver_lng }}]).addTo(map)
                .bindPopup("Driver: {{ $ride->driver->fullname ?? 'N/A' }} <br> Ride #{{ $ride->id }}");
        @endif
    @endforeach
</script>

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
