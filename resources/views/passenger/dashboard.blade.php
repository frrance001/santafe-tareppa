@extends('layouts.passenger')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');

    body {
        background: url('/images') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
        position: relative;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(248, 242, 244, 0.75);
        z-index: -1;
    }

    .animated-welcome {
        font-family: 'Poppins', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: orange;
        animation: slideFade 1s ease-out forwards;
        opacity: 0;
        margin-bottom: 30px;
    }

    @keyframes slideFade {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .glass-box {
        background: rgba(250, 246, 246, 0.947);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid rgba(243, 241, 236, 0.941);
        padding: 25px;
        box-shadow: 0 8px 24px rgba(253, 251, 251, 0.25);
    }

    .table {
        background-color: rgba(255, 255, 255, 0.85);
        color: #000;
    }

    .table th, .table td {
        border-color: #ddd;
    }

    .alert {
        background-color: rgba(255, 204, 0, 0.2);
        color: #000;
        border-color: #ffa500;
    }

    #map {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        z-index: 1;
    }
</style>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-5">
    <h1 class="animated-welcome text-center">Welcome, Passenger!</h1>

    <div class="glass-box mb-5">
        <h3 class="mb-3">Your Location</h3>
        <div id="map"></div>
    </div>

    <div class="glass-box">
        <h3 class="mb-4">Ride History</h3>
        @if($rides->isEmpty())
            <div class="alert alert-info">No rides found.</div>
        @else
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pickup</th>
                        <th>Drop-off</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rides as $ride)
                        <tr>
                            <td>{{ $ride->pickup_location }}</td>
                            <td>{{ $ride->dropoff_location }}</td>
                            <td>
                                @if($ride->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($ride->status === 'accepted')
                                    <span class="badge bg-success">Accepted</span>
                                @elseif($ride->status === 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($ride->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span id="ride-date-{{ $ride->id }}">
                                    {{ $ride->created_at->format('M d, Y h:i A') }}
                                </span>
                            </td>
                            <td>
                                @if($ride->status === 'completed')
                                    <button type="button" class="btn btn-danger btn-sm delete-ride" data-id="{{ $ride->id }}">
                                        🗑️ Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const geoapifyKey = "da1d5d28dc354b6ea277eae05b50312b";

    // ✅ Use Geoapify Light Map
    var map = L.map('map').setView([11.1951, 123.6929], 13);
    L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=${geoapifyKey}`, {
        attribution: '&copy; <a href="https://www.geoapify.com/">Geoapify</a>',
        maxZoom: 20
    }).addTo(map);

    var marker = null;
    const userEmail = "{{ Auth::user()->email ?? '' }}";

    if (!userEmail) {
        alert("❌ No logged-in user detected.");
        return;
    }

    // ✅ Live location tracking
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, handleError);
        navigator.geolocation.watchPosition(showPosition);
    } else {
        alert("❌ Your browser doesn't support GPS.");
    }

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        map.setView([lat, lng], 15);
        if (marker) {
            marker.setLatLng([lat, lng]).bindPopup("Your Location").openPopup();
        } else {
            marker = L.marker([lat, lng]).addTo(map).bindPopup("Your Location").openPopup();
        }

        updateUserLocation(userEmail, lat, lng);
    }

    function handleError() {
        alert("⚠️ GPS access denied. Showing default location.");
        map.setView([11.1951, 123.6929], 13);
        L.marker([11.1951, 123.6929]).addTo(map).bindPopup("Default Location").openPopup();
    }

    // ✅ Send coordinates to backend
    function updateUserLocation(email, lat, lng) {
        fetch("{{ route('update.location') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email, latitude: lat, longitude: lng })
        }).catch(err => console.error("Location update failed:", err));
    }

    // ✅ SweetAlert2 delete confirmation
    document.querySelectorAll('.delete-ride').forEach(button => {
        button.addEventListener('click', function () {
            const rideId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This ride history will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/passenger/ride/${rideId}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // ✅ Real-time clock (updates every second)
    setInterval(() => {
        document.querySelectorAll('[id^="ride-date-"]').forEach(span => {
            let now = new Date();
            span.innerText = now.toLocaleString('en-US', {
                month: 'short', day: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
            });
        });
    }, 1000);

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        }).then(() => {
            window.location.href = "{{ route('passenger.dashboard') }}";
        });
    @endif
});
</script>
@endsection
