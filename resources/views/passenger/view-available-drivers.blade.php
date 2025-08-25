@extends('layouts.passenger')

@section('content')
<style>
    body {
        background: url('/images/tric.png') no-repeat center center fixed;
        background-size: cover;
        position: relative;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: -1;
    }
    .glass-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 30px;
        color: #fff;
        margin-bottom: 40px;
    }
    #map {
        width: 100%;
        height: 500px;
        border-radius: 12px;
        margin-bottom: 15px;
    }
</style>

<div class="container my-5 glass-box">
    <h2 class="mb-4">🚖 Available Drivers</h2>

    @if($drivers->isEmpty())
        <div class="alert alert-info">No drivers are currently available.</div>
    @endif

    <!-- Map -->
    <div id="map"></div>

    <!-- Drivers Table -->
    @if(!$drivers->isEmpty())
        <table class="table table-bordered table-hover text-white mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $driver)
                    <tr>
                        <td>{{ $driver->fullname }}</td>
                        <td>{{ $driver->email }}</td>
                        <td>{{ $driver->phone ?? 'N/A' }}</td>
                        <td>{{ ucfirst($driver->sex ?? 'N/A') }}</td>
                        <td>{{ $driver->age ?? 'N/A' }}</td>
                        <td>
                            @if($driver->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            @if($driver->is_available)
                                <button class="btn btn-primary btn-sm request-btn" 
                                        data-driver="{{ $driver->id }}" 
                                        data-name="{{ $driver->fullname }}">
                                    Request Ride
                                </button>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>Unavailable</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Ride Request Modal -->
<div class="modal fade" id="rideModal" tabindex="-1" aria-labelledby="rideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-light" style="background: rgba(0,0,0,0.85); border-radius: 12px;">
            <div class="modal-header">
                <h5 class="modal-title" id="rideModalLabel">🚖 Request a Ride</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rideForm" action="{{ route('passenger.request.store') }}" method="POST">
                @csrf
                <input type="hidden" name="driver_id" id="driver_id">

                <div class="modal-body">
                    <div class="alert alert-info text-dark" id="driverInfo"></div>

                    <div class="mb-3">
                        <label for="pickup_location" class="form-label">📍 Pickup Location:</label>
                        <input type="text" name="pickup_location" id="pickup_location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="dropoff_location" class="form-label">🏁 Drop-off Location:</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">📞 Phone Number:</label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="09123456789" pattern="[0-9]{11}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="bookBtn" class="btn btn-warning">Book Ride</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Esri Geocoder -->
<script src="https://unpkg.com/esri-leaflet"></script>
<script src="https://unpkg.com/esri-leaflet-geocoder"></script>
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"/>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const drivers = @json($drivers);
    const map = L.map('map').setView([11.1951, 123.6929], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let pickupMarker, dropoffMarker;

    // Real-time Passenger GPS location
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (pickupMarker) {
                pickupMarker.setLatLng([lat, lng]);
            } else {
                pickupMarker = L.marker([lat, lng], { 
                    icon: L.icon({iconUrl:'/images/passenger.png', iconSize:[35,35]})
                }).addTo(map).bindPopup("📍 You are here").openPopup();
            }

            map.setView([lat, lng], 14);
        }, function() {
            alert("⚠️ Unable to get your GPS location. Default location shown.");
        });
    }

    // Show all available drivers
    drivers.forEach(driver => {
        if(driver.is_available && driver.lat && driver.lng) {
            L.marker([driver.lat, driver.lng], { 
                icon: L.icon({iconUrl:'/images/driver.png', iconSize:[40,40]})
            }).addTo(map)
            .bindPopup(`<strong>${driver.fullname}</strong><br>${driver.phone ?? ''}`);
        }
    });

    // Esri geocoder for pickup/dropoff
    const pickupInput = document.getElementById("pickup_location");
    const dropoffInput = document.getElementById("dropoff_location");

    pickupInput.addEventListener("change", function () {
        L.esri.Geocoding.geocode().text(this.value).run(function (err, results) {
            if (results && results.results.length > 0) {
                const latlng = results.results[0].latlng;
                if(pickupMarker) map.removeLayer(pickupMarker);
                pickupMarker = L.marker(latlng, { draggable:true }).addTo(map).bindPopup("Pickup").openPopup();
                map.setView(latlng, 15);
                updateETA();
            }
        });
    });

    dropoffInput.addEventListener("change", function () {
        L.esri.Geocoding.geocode().text(this.value).run(function (err, results) {
            if (results && results.results.length > 0) {
                const latlng = results.results[0].latlng;
                if(dropoffMarker) map.removeLayer(dropoffMarker);
                dropoffMarker = L.marker(latlng, { draggable:true }).addTo(map).bindPopup("Drop-off").openPopup();
                map.setView(latlng, 15);
                updateETA();
            }
        });
    });

    // Estimate travel time between pickup and dropoff
    function calculateETA(pickupLatLng, dropLatLng) {
        const url = `https://router.project-osrm.org/route/v1/driving/${pickupLatLng.lng},${pickupLatLng.lat};${dropLatLng.lng},${dropLatLng.lat}?overview=false`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if(data.routes && data.routes.length > 0) {
                    const seconds = data.routes[0].duration;
                    const minutes = Math.round(seconds / 60);
                    Swal.fire({
                        icon: 'info',
                        title: 'Estimated Travel Time',
                        text: `⏱️ Approximately ${minutes} minutes from pickup to drop-off.`,
                        timer: 4000,
                        showConfirmButton: false
                    });
                }
            });
    }

    function updateETA() {
        if(pickupMarker && dropoffMarker) {
            calculateETA(pickupMarker.getLatLng(), dropoffMarker.getLatLng());
        }
    }

    // Request ride modal
    const requestBtns = document.querySelectorAll('.request-btn');
    const driverIdField = document.getElementById('driver_id');
    const driverInfo = document.getElementById('driverInfo');

    requestBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            driverIdField.value = this.dataset.driver;
            driverInfo.innerHTML = `You are requesting <strong>${this.dataset.name}</strong>`;
            new bootstrap.Modal(document.getElementById('rideModal')).show();
        });
    });

    // Autocomplete suggestions
    L.esri.Geocoding.geosearch({
        placeholder: 'Search for location...',
        useMapBounds: false,
        providers: [L.esri.Geocoding.arcgisOnlineProvider({ countries: 'PH' })]
    }).addTo(map);

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Ride Requested',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        });
    @endif
});
</script>
@endsection