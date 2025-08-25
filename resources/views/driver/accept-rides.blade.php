@extends('layouts.driver')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Side: Ride Requests -->
        <div class="col-md-4 p-3 border-end">
            <h2 class="fw-bold mb-3 text-primary">📋 Available Ride Requests</h2>
            @if($rides->isEmpty())
                <p>No ride requests available.</p>
            @else
                <ul class="list-group" id="rideList">
                    @foreach($rides as $ride)
                        <li class="list-group-item ride-item mb-2 shadow-sm rounded"
                            style="cursor: pointer;"
                            data-bs-id="{{ $ride->id }}"
                            data-passenger-name="{{ $ride->user->fullname ?? 'N/A' }}"
                            data-passenger-email="{{ $ride->user->email ?? 'N/A' }}"
                            data-passenger-phone="{{ $ride->phone_number ?? 'N/A' }}"
                            data-pickup-lat="{{ $ride->pickup_lat }}"
                            data-pickup-lng="{{ $ride->pickup_lng }}"
                            data-dropoff-lat="{{ $ride->dropoff_lat }}"
                            data-dropoff-lng="{{ $ride->dropoff_lng }}"
                            data-pickup-location="{{ $ride->pickup_location }}"
                            data-dropoff-location="{{ $ride->dropoff_location }}">
                            <strong class="passenger-link text-primary" style="cursor:pointer;">
                                {{ $ride->user->fullname ?? 'N/A' }}
                            </strong><br>
                            <small class="text-muted">From: {{ $ride->pickup_location }}</small><br>
                            <small class="text-muted">To: {{ $ride->dropoff_location }}</small><br>
                            <small>Status: <span class="badge bg-info">{{ ucfirst($ride->status) }}</span></small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Right Side: Map and Details -->
        <div class="col-md-8 p-3">
            <h3 class="fw-bold mb-3 text-success">🗺 Ride Location & Passenger Info</h3>
            <div class="mb-3 p-3 bg-light rounded shadow-sm">
                <p><strong>Passenger Name:</strong> <span id="passengerName">—</span></p>
                <p><strong>Email:</strong> <span id="passengerEmail">—</span></p>
                <p><strong>Phone:</strong> <span id="passengerPhone">—</span></p>
            </div>

            <!-- Input for pickup & dropoff -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pickupLocationInput" class="form-label"><strong>Pickup Location:</strong></label>
                    <input type="text" id="pickupLocationInput" name="pickup_location" class="form-control" placeholder="Search pickup location">
                </div>
                <div class="col-md-6">
                    <label for="dropoffLocationInput" class="form-label"><strong>Drop-off Location:</strong></label>
                    <input type="text" id="dropoffLocationInput" name="dropoff_location" class="form-control" placeholder="Search dropoff location">
                </div>
            </div>

            <div id="rideMap" style="height: 500px; width: 100%;" class="rounded shadow"></div>

            <form id="acceptForm" method="POST" action="" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-success w-100">✅ Accept Ride</button>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Leaflet Geocoder (for autocomplete) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const acceptForm = document.getElementById('acceptForm');
    const passengerNameSpan = document.getElementById('passengerName');
    const passengerEmailSpan = document.getElementById('passengerEmail');
    const passengerPhoneSpan = document.getElementById('passengerPhone');
    const pickupLocationInput = document.getElementById('pickupLocationInput');
    const dropoffLocationInput = document.getElementById('dropoffLocationInput');

    // Init map
    let map = L.map('rideMap').setView([10.3157, 123.8854], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    let pickupMarker, dropoffMarker;

    const greenIcon = new L.Icon({
        iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
        iconSize: [32, 32], iconAnchor: [16, 32]
    });
    const redIcon = new L.Icon({
        iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
        iconSize: [32, 32], iconAnchor: [16, 32]
    });

    // Ride item click
    document.querySelectorAll('.ride-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.ride-item').forEach(el => el.classList.remove('active'));
            item.classList.add('active');

            const rideId = item.dataset.bsId;
            const passengerName = item.dataset.passengerName;
            const passengerEmail = item.dataset.passengerEmail;
            const passengerPhone = item.dataset.passengerPhone;
            const pickupLat = parseFloat(item.dataset.pickupLat);
            const pickupLng = parseFloat(item.dataset.pickupLng);
            const dropoffLat = parseFloat(item.dataset.dropoffLat);
            const dropoffLng = parseFloat(item.dataset.dropoffLng);
            const pickupLocation = item.dataset.pickupLocation;
            const dropoffLocation = item.dataset.dropoffLocation;

            // Set form action
            acceptForm.action = `/driver/accept-rides/${rideId}/accept`;

            // Fill passenger info
            passengerNameSpan.textContent = passengerName;
            passengerEmailSpan.textContent = passengerEmail;
            passengerPhoneSpan.textContent = passengerPhone;

            // Auto-fill pickup/dropoff inputs
            pickupLocationInput.value = pickupLocation;
            dropoffLocationInput.value = dropoffLocation;

            // Clear old markers
            if (pickupMarker) map.removeLayer(pickupMarker);
            if (dropoffMarker) map.removeLayer(dropoffMarker);

            // Add new markers
            pickupMarker = L.marker([pickupLat, pickupLng], { icon: greenIcon }).addTo(map)
                .bindPopup(`<b>Pickup:</b> ${pickupLocation}<br><b>Passenger:</b> ${passengerName}`).openPopup();
            dropoffMarker = L.marker([dropoffLat, dropoffLng], { icon: redIcon }).addTo(map)
                .bindPopup(`<b>Drop-off:</b> ${dropoffLocation}`);

            // Fit map to show both markers
            let bounds = L.latLngBounds([[pickupLat, pickupLng], [dropoffLat, dropoffLng]]);
            map.fitBounds(bounds, { padding: [50, 50] });
        });
    });

    // Add geocoder search for pickup
    let pickupGeocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        let latlng = e.geocode.center;
        pickupLocationInput.value = e.geocode.name;

        if (pickupMarker) map.removeLayer(pickupMarker);
        pickupMarker = L.marker(latlng, { icon: greenIcon }).addTo(map)
            .bindPopup(`<b>Pickup:</b> ${e.geocode.name}`).openPopup();

        map.setView(latlng, 15);
    });

    // Add geocoder search for dropoff
    let dropoffGeocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        let latlng = e.geocode.center;
        dropoffLocationInput.value = e.geocode.name;

        if (dropoffMarker) map.removeLayer(dropoffMarker);
        dropoffMarker = L.marker(latlng, { icon: redIcon }).addTo(map)
            .bindPopup(`<b>Drop-off:</b> ${e.geocode.name}`).openPopup();

        map.setView(latlng, 15);
    });

    // Bind geocoder search to inputs
    pickupLocationInput.addEventListener('focus', () => pickupGeocoder.addTo(map));
    dropoffLocationInput.addEventListener('focus', () => dropoffGeocoder.addTo(map));
});
</script>
@endsection
