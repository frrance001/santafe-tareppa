@extends('layouts.admin')

@section('content')
<style>
    body {
        background: #ffffff !important;
        color: #000;
    }

    .glass-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-weight: bold;
        color: #1e3a8a;
    }

    table {
        background-color: #ffffff;
        color: #000;
    }

    th {
        background-color: #f3f4f6;
        color: #000;
    }

    tr:hover {
        background-color: #f9fafb;
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

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Leaflet + Geoapify -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div class="container mt-5 glass-container">
    <h1 class="mb-4 text-center">Completed Rides</h1>

    <!-- 🌍 Geoapify Map -->
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

<!-- ✅ Map Script -->
<script>
    // Initialize Geoapify map
    var map = L.map('map').setView([11.1951, 123.6929], 12);

    // ✅ Geoapify Tile Layer (replace with your API key)
    L.tileLayer(`https://maps.geoapify.com/v1/tile/positron/{z}/{x}/{y}.png?apiKey=da1d5d28dc354b6ea277eae05b50312b`, {
        maxZoom: 20,
        attribution: '&copy; OpenStreetMap contributors | © Geoapify'
    }).addTo(map);

    // ✅ Add ride markers
    @foreach($rides as $ride)
        @if(isset($ride->pickup_lat, $ride->pickup_lng, $ride->dropoff_lat, $ride->dropoff_lng))
            // Pickup marker
            var pickupMarker = L.marker([{{ $ride->pickup_lat }}, {{ $ride->pickup_lng }}])
                .addTo(map)
                .bindPopup("<strong>Pickup:</strong> {{ $ride->pickup_location }}<br><strong>Ride #{{ $ride->id }}</strong>");

            // Dropoff marker
            var dropoffMarker = L.marker([{{ $ride->dropoff_lat }}, {{ $ride->dropoff_lng }}])
                .addTo(map)
                .bindPopup("<strong>Dropoff:</strong> {{ $ride->dropoff_location }}<br><strong>Ride #{{ $ride->id }}</strong>");

            // Draw line between pickup and dropoff
            var latlngs = [
                [{{ $ride->pickup_lat }}, {{ $ride->pickup_lng }}],
                [{{ $ride->dropoff_lat }}, {{ $ride->dropoff_lng }}]
            ];
            var polyline = L.polyline(latlngs, {color: '#1e3a8a', weight: 3, opacity: 0.8}).addTo(map);
        @endif

        @if(isset($ride->driver_lat, $ride->driver_lng))
            // Driver marker
            var driverMarker = L.marker([{{ $ride->driver_lat }}, {{ $ride->driver_lng }}], {
                icon: L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/1946/1946411.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(map)
            .bindPopup("<strong>Driver:</strong> {{ $ride->driver->fullname ?? 'N/A' }}<br><strong>Ride #{{ $ride->id }}</strong>");
        @endif
    @endforeach
</script>

<!-- ✅ Delete Confirmation -->
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
