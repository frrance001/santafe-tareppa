@extends('layouts.driver')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Panel: Ride Requests -->
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Available Ride Requests</h5>
                </div>
                <div class="card-body p-0">
                    @if($rides->isEmpty())
                        <p class="text-center my-3">No ride requests available.</p>
                    @else
                        <ul class="list-group list-group-flush" id="rideList">
                            @foreach($rides as $ride)
                                <li class="list-group-item ride-item d-flex flex-column justify-content-between mb-2 rounded shadow-sm"
                                    id="ride-{{ $ride->id }}"
                                    style="cursor: pointer;"
                                    data-bs-id="{{ $ride->id }}"
                                    data-pickup-lat="{{ $ride->pickup_lat }}"
                                    data-pickup-lng="{{ $ride->pickup_lng }}"
                                    data-dropoff-lat="{{ $ride->dropoff_lat }}"
                                    data-dropoff-lng="{{ $ride->dropoff_lng }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-primary">{{ $ride->user->fullname ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">From: {{ $ride->pickup_location }}</small><br>
                                            <small class="text-muted">To: {{ $ride->dropoff_location }}</small>
                                        </div>
                                        <span class="badge bg-info">{{ ucfirst($ride->status) }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Panel: Map Only -->
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"> Ride Map</h5>
                </div>
                <div class="card-body">
                    <div id="rideMap" class="rounded shadow mb-3" style="height: 450px;"></div>

                    <form id="acceptForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 fw-bold">Accept Ride</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const acceptForm = document.getElementById('acceptForm');
    let selectedRideId = null;

    // Initialize map
    const map = L.map('rideMap').setView([10.3157, 123.8854], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    let pickupMarker = null;
    let dropoffMarker = null;
    let routeLine = null;

    const greenIcon = new L.Icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png', iconSize: [32,32], iconAnchor: [16,32] });
    const redIcon = new L.Icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png', iconSize: [32,32], iconAnchor: [16,32] });

    function drawRoute(pLat, pLng, dLat, dLng) {
        if(routeLine) map.removeLayer(routeLine);
        routeLine = L.polyline([[pLat,pLng],[dLat,dLng]], { color:'blue', weight:5, opacity:0.6 }).addTo(map);
        map.fitBounds(routeLine.getBounds(), { padding:[50,50] });
    }

    // Ride item click
    document.querySelectorAll('.ride-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.ride-item').forEach(el => el.classList.remove('active'));
            item.classList.add('active');

            selectedRideId = item.dataset.bsId;

            const pLat = parseFloat(item.dataset.pickupLat);
            const pLng = parseFloat(item.dataset.pickupLng);
            const dLat = parseFloat(item.dataset.dropoffLat);
            const dLng = parseFloat(item.dataset.dropoffLng);

            if(pickupMarker) map.removeLayer(pickupMarker);
            if(dropoffMarker) map.removeLayer(dropoffMarker);

            pickupMarker = L.marker([pLat,pLng], { icon: greenIcon }).addTo(map).bindPopup("Pickup Location").openPopup();
            dropoffMarker = L.marker([dLat,dLng], { icon: redIcon }).addTo(map).bindPopup("Drop-off Location");

            drawRoute(pLat, pLng, dLat, dLng);
        });
    });

    // Accept ride AJAX
    acceptForm.addEventListener('submit', function(e){
        e.preventDefault();
        if(!selectedRideId){
            Swal.fire('Please select a ride first!');
            return;
        }

        fetch(`/driver/accept-rides/${selectedRideId}/accept`, {
            method:'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type':'application/json' },
            body: JSON.stringify({})
        })
        .then(async res => { 
            let data; 
            try { data = await res.json(); } 
            catch(e) { data = { success:true, message:'Ride accepted successfully!' }; }
            return data;
        })
        .then(data => {
            if(data.success){
                Swal.fire('Ride Accepted!', data.message, 'success');
                const rideItem = document.getElementById(`ride-${selectedRideId}`);
                if(rideItem) rideItem.remove();
                selectedRideId = null;
                document.querySelectorAll('.ride-item').forEach(el => el.classList.remove('active'));
                if(pickupMarker) map.removeLayer(pickupMarker);
                if(dropoffMarker) map.removeLayer(dropoffMarker);
                if(routeLine) map.removeLayer(routeLine);
            } else {
                Swal.fire('❌ Failed', data.message || 'Something went wrong.', 'error');
            }
        })
        .catch(() => { Swal.fire('❌ Error', 'Could not accept ride.', 'error'); });
    });
});
</script>
@endsection
