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

            $activeRide = \App\Models\Ride::where('driver_id', auth()->id())
                        ->where('status', 'in_progress')
                        ->latest()
                        ->first();
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

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ Pusher & Laravel Echo --}}
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script> {{-- Make sure Echo is configured --}}

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Default map
    let map = L.map('map').setView([11.1951, 123.6929], 13);

    // Tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Driver marker
    let driverMarker = L.marker([11.1951, 123.6929]).addTo(map).bindPopup("Your Location");

    // Destination marker (if in-progress ride)
    @if($activeRide && $activeRide->destination_lat && $activeRide->destination_lng)
        let destLat = {{ $activeRide->destination_lat }};
        let destLng = {{ $activeRide->destination_lng }};
        let destinationMarker = L.marker([destLat, destLng], {
            icon: L.icon({
                iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
                iconSize: [32, 32]
            })
        }).addTo(map).bindPopup("Destination");

        let routeLine = L.polyline([], {color: 'blue'}).addTo(map);
    @endif

    // Update driver location in real-time
    function updateLocation(position) {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        document.getElementById("lat").textContent = lat.toFixed(6);
        document.getElementById("lon").textContent = lon.toFixed(6);

        driverMarker.setLatLng([lat, lon]);
        map.setView([lat, lon], 15);

        @if($activeRide && $activeRide->destination_lat && $activeRide->destination_lng)
            routeLine.setLatLngs([[lat, lon], [destLat, destLng]]);
        @endif
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

    // ✅ Laravel Echo listener for new ride requests
    Echo.private(`driver.{{ auth()->id() }}`)
        .listen('RideRequested', (e) => {
            Swal.fire({
                icon: 'info',
                title: '🚖 New Ride Request!',
                html: `
                    <strong>Passenger:</strong> ${e.passengerName}<br>
                    <strong>Pickup:</strong> ${e.pickup}<br>
                    <strong>Drop-off:</strong> ${e.dropoff}
                `,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });

            // Play sound alert
            const audio = new Audio('/sounds/notification.mp3');
            audio.play();
        });
});
</script>
@endsection
