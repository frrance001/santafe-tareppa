@extends('layouts.passenger')

@section('content')
<style>
body {
    background: url('/images') no-repeat center center fixed;
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
    background: rgb(254, 248, 248);
    z-index: -1;
}
.glass-box {
    background: rgb(242, 235, 235);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 16px;
    border: 1px solid rgba(246, 243, 243, 0.99);
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
table tbody tr td {
    color: #000;
}
</style>

<div class="container my-5 glass-box">
    <h2 class="mb-4">Available Drivers</h2>

    @if($drivers->isEmpty())
        <div class="alert alert-info text-dark">No drivers are currently available.</div>
    @endif

    <div id="map"></div>

    @if(!$drivers->isEmpty())
        <table class="table table-bordered table-hover text-dark mt-3">
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
        <div class="modal-content text-light" style="background: rgba(177, 198, 198, 0.908); border-radius: 12px;">
            <div class="modal-header">
                <h5 class="modal-title" id="rideModalLabel">Request a Ride</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rideForm" action="{{ route('passenger.request.store') }}" method="POST">
                @csrf
                <input type="hidden" name="driver_id" id="driver_id">

                <div class="modal-body">
                    <div class="alert alert-info text-dark" id="driverInfo"></div>

                    <div class="mb-3">
                        <label for="pickup_location" class="form-label">Pickup Location:</label>
                        <input type="text" name="pickup_location" id="pickup_location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="dropoff_location" class="form-label">Drop-off Location:</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number:</label>
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

<!-- Leaflet + Plugins -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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

    const driverIcon = L.icon({iconUrl:'/images/tricycle.png', iconSize:[40,40]});
    const passengerIcon = L.icon({iconUrl:'/images/passenger.png', iconSize:[35,35]});
    const pickupIcon = L.icon({iconUrl:'/images/marker.jpg', iconSize:[35,35]});
    const dropoffIcon = L.icon({iconUrl:'/images/marker.jpg', iconSize:[35,35]});

    let pickupMarker = null;
    let dropoffMarker = null;
    let routeLayer = null;

    const pickupInput = document.getElementById("pickup_location");
    const dropoffInput = document.getElementById("dropoff_location");

    // Show available drivers on map
    drivers.forEach(driver => {
        if(driver.is_available && driver.lat && driver.lng) {
            L.marker([driver.lat, driver.lng], { icon: driverIcon })
             .addTo(map)
             .bindPopup(`<strong>${driver.fullname}</strong><br>${driver.phone ?? ''}`);
        }
    });

    // Show passenger location if available
    if(navigator.geolocation){
        navigator.geolocation.watchPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            if(!pickupMarker){
                pickupMarker = L.marker([lat,lng], { icon: passengerIcon, draggable:true })
                                 .addTo(map).bindPopup("You are here").openPopup();
            } else {
                pickupMarker.setLatLng([lat,lng]);
            }
            map.setView([lat,lng],14);
        });
    }

    // Map click to set pickup/drop-off
    let settingPickup = true;
    map.on('click', function(e){
        const latlng = e.latlng;
        if(settingPickup){
            if(pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(latlng, { icon: pickupIcon, draggable:true })
                .addTo(map).bindPopup("Pickup Location").openPopup();
            pickupInput.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
            settingPickup = false;
        } else {
            if(dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker(latlng, { icon: dropoffIcon, draggable:true })
                .addTo(map).bindPopup("Drop-off Location").openPopup();
            dropoffInput.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
            settingPickup = true;
        }
        updateRoute();
    });

    function debounce(func, delay){
        let timeout;
        return function(){
            clearTimeout(timeout);
            timeout = setTimeout(func, delay);
        }
    }

    function addMarker(inputEl, icon, isPickup=true){
        const value = inputEl.value;
        if(!value) return;
        L.esri.Geocoding.geocode().text(value).run((err, results) => {
            if(!results || !results.results.length) return;
            const latlng = results.results[0].latlng;
            if(isPickup){
                if(pickupMarker) map.removeLayer(pickupMarker);
                pickupMarker = L.marker(latlng, {icon:icon, draggable:true}).addTo(map).bindPopup("Pickup").openPopup();
            } else {
                if(dropoffMarker) map.removeLayer(dropoffMarker);
                dropoffMarker = L.marker(latlng, {icon:icon, draggable:true}).addTo(map).bindPopup("Drop-off").openPopup();
            }
            map.setView(latlng, 15);
            updateRoute();
        });
    }

    pickupInput.addEventListener("input", debounce(() => addMarker(pickupInput, pickupIcon, true), 800));
    dropoffInput.addEventListener("input", debounce(() => addMarker(dropoffInput, dropoffIcon, false), 800));

    function updateRoute(){
        if(pickupMarker && dropoffMarker){
            const p = pickupMarker.getLatLng();
            const d = dropoffMarker.getLatLng();
            const url = `https://router.project-osrm.org/route/v1/driving/${p.lng},${p.lat};${d.lng},${d.lat}?overview=full&geometries=geojson`;
            fetch(url).then(res => res.json()).then(data => {
                if(data.routes && data.routes.length > 0){
                    const route = data.routes[0].geometry;
                    if(routeLayer) map.removeLayer(routeLayer);
                    routeLayer = L.geoJSON(route, {color:'blue', weight:5, opacity:0.7}).addTo(map);

                    const mins = Math.round(data.routes[0].duration / 60);
                    const fare = mins * 10;

                    Swal.fire({
                        icon: 'info',
                        title: 'Estimated Travel Info',
                        html: `
                            <strong>Time:</strong> Approximately ${mins} minutes<br>
                            <strong>Fare:</strong> ₱${fare.toFixed(2)}
                        `,
                        timer: 5000,
                        showConfirmButton: false
                    });
                }
            });
        }
    }

    // Handle ride request
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

    const rideForm = document.getElementById('rideForm');
    const bookBtn = document.getElementById('bookBtn');
    rideForm.addEventListener('submit', function(){
        bookBtn.disabled = true;
        bookBtn.textContent = "Booking...";
    });

    @if(session('success'))
        Swal.fire({icon:'success', title:'Ride Requested', text:'{{ session('success') }}', confirmButtonColor:'#198754'});
    @endif
    @if(session('error'))
        Swal.fire({icon:'error', title:'Error', text:'{{ session('error') }}', confirmButtonColor:'#dc3545'});
    @endif
});
</script>
@endsection 