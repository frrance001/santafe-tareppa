@extends('layouts.driver')

@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-3">Driver GPS Tracker</h2>

    {{-- Map --}}
    <div id="map" style="height: 500px; border-radius: 10px; border: 1px solid #ccc;"></div>

    {{-- Driver Location Info --}}
    <div class="mt-3 p-3 bg-light rounded shadow-sm">
        <strong>Latitude:</strong> <span id="lat">--</span><br>
        <strong>Longitude:</strong> <span id="lon">--</span>
    </div>

    {{-- Booking History --}}
    <div class="mt-5">
        <h3 class="mb-3">Booking History</h3>
        @php
            $bookings = \App\Models\Ride::where('driver_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();
        @endphp

        @if($bookings->isEmpty())
            <p class="text-muted">No previous bookings.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Passenger</th>
                            <th>Pickup</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $ride)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ride->user->fullname ?? 'N/A' }}</td>
                            <td>{{ $ride->pickup_location }}</td>
                            <td>{{ $ride->destination }}</td>
                            <td>
                                @if($ride->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($ride->status === 'in_progress')
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $ride->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize map
    let map = L.map('map').setView([11.1951, 123.6929], 13); // Default center

    // Tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Driver marker
    let driverMarker = L.marker([11.1951, 123.6929]).addTo(map).bindPopup("Your Location");

    // Update driver location every 5 seconds
    function updateLocation(position) {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        document.getElementById("lat").textContent = lat.toFixed(6);
        document.getElementById("lon").textContent = lon.toFixed(6);

        driverMarker.setLatLng([lat, lon]);
        map.setView([lat, lon], 15);
    }

    function errorHandler(err) {
        alert("Unable to retrieve location: " + err.message);
    }

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updateLocation, errorHandler, {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 5000
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
});
</script>
@endsection
