@extends('layouts.passenger')

@section('content')
<style>
    /* 🌄 Background & Glass Box */
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
        color: #000;
        margin-bottom: 40px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    h2 {
        color: #222;
        font-weight: bold;
        text-align: center;
    }

    /* 🗺️ Map Styling */
    #map {
        width: 100%;
        height: 500px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    /* 📋 Table Styling */
    table {
        width: 100%;
    }
    table thead {
        background: #2f80ed;
        color: #fff;
    }
    table tbody tr td {
        color: #000;
        vertical-align: middle;
    }
    table tbody tr:hover {
        background: rgba(47, 128, 237, 0.1);
        transition: 0.3s;
    }

    /* 🧾 Modal Styling */
    .modal-content {
        background: rgba(177, 198, 198, 0.93);
        border-radius: 12px;
        border: none;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .modal-header, .modal-footer {
        border: none;
    }
    .modal-title {
        font-weight: 600;
    }
    label {
        font-weight: 500;
        color: #222;
    }

    /* ✅ Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    .btn-primary {
        background-color: #2f80ed;
        border: none;
    }
    .btn-warning {
        color: #000;
        font-weight: 600;
    }

    /* 💻 RESPONSIVE DESIGN */
    @media (max-width: 992px) {
        #map {
            height: 400px;
        }
        .glass-box {
            padding: 20px;
        }
        h2 {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        #map {
            height: 350px;
        }
        h2 {
            font-size: 1.6rem;
        }
        .glass-box {
            padding: 15px;
        }
        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
        }
        table {
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .btn {
            font-size: 0.85rem;
            padding: 6px 10px;
        }
    }

    @media (max-width: 576px) {
        #map {
            height: 300px;
        }
        .glass-box {
            padding: 10px;
        }
        h2 {
            font-size: 1.4rem;
        }
        .modal-dialog {
            max-width: 95%;
            margin: auto;
        }
        .modal-body {
            font-size: 0.9rem;
        }
        .btn {
            width: 100%;
            margin-top: 5px;
        }
    }
</style>

<div class="container my-5 glass-box">
    <h2 class="mb-4">Available Drivers</h2>

    @if($drivers->isEmpty())
        <div class="alert alert-info text-dark text-center">No drivers are currently available.</div>
    @endif

    <div id="map"></div>

    @if(!$drivers->isEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-dark mt-3 align-middle">
                <thead>
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
        </div>
    @endif
</div>

<!-- Ride Request Modal -->
<div class="modal fade" id="rideModal" tabindex="-1" aria-labelledby="rideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-light">
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

                <div class="modal-footer flex-column flex-md-row">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    // Show drivers
    drivers.forEach(driver => {
        if(driver.is_available && driver.lat && driver.lng) {
            L.marker([driver.lat, driver.lng], { icon: driverIcon })
             .addTo(map)
             .bindPopup(`<strong>${driver.fullname}</strong><br>${driver.phone ?? ''}`);
        }
    });

    // Passenger location
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

    // Map click
    let settingPickup = true;
    map.on('click', function(e){
        const latlng = e.latlng;
        if(settingPickup){
            if(pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(latlng, { icon: pickupIcon, draggable:true })
                .addTo(map).bindPopup("Pickup").openPopup();
            pickupInput.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
            settingPickup = false;
        } else {
            if(dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker(latlng, { icon: dropoffIcon, draggable:true })
                .addTo(map).bindPopup("Drop-off").openPopup();
            dropoffInput.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
            settingPickup = true;
        }
        updateRoute();
    });

    function debounce(func, delay){ let t; return function(){clearTimeout(t); t=setTimeout(func, delay);} }
    function addMarker(inputEl, icon, isPickup=true){
        const val=inputEl.value; if(!val) return;
        L.esri.Geocoding.geocode().text(val).run((err,res)=>{
            if(!res?.results?.length) return;
            const ll=res.results[0].latlng;
            if(isPickup){
                if(pickupMarker) map.removeLayer(pickupMarker);
                pickupMarker=L.marker(ll,{icon:icon,draggable:true}).addTo(map).bindPopup("Pickup").openPopup();
            } else {
                if(dropoffMarker) map.removeLayer(dropoffMarker);
                dropoffMarker=L.marker(ll,{icon:icon,draggable:true}).addTo(map).bindPopup("Drop-off").openPopup();
            }
            map.setView(ll,15);
            updateRoute();
        });
    }
    pickupInput.addEventListener("input", debounce(()=>addMarker(pickupInput,pickupIcon,true),800));
    dropoffInput.addEventListener("input", debounce(()=>addMarker(dropoffInput,dropoffIcon,false),800));

    function updateRoute(){
        if(pickupMarker && dropoffMarker){
            const p=pickupMarker.getLatLng(), d=dropoffMarker.getLatLng();
            const url=`https://router.project-osrm.org/route/v1/driving/${p.lng},${p.lat};${d.lng},${d.lat}?overview=full&geometries=geojson`;
            fetch(url).then(r=>r.json()).then(data=>{
                if(data.routes?.length){
                    const route=data.routes[0].geometry;
                    if(routeLayer) map.removeLayer(routeLayer);
                    routeLayer=L.geoJSON(route,{color:'blue',weight:5,opacity:0.7}).addTo(map);
                    const mins=Math.round(data.routes[0].duration/60);
                    const fare=mins*10;
                    Swal.fire({
                        icon:'info',
                        title:'Estimated Travel Info',
                        html:`<strong>Time:</strong> ${mins} mins<br><strong>Fare:</strong> ₱${fare.toFixed(2)}`,
                        timer:5000,
                        showConfirmButton:false
                    });
                }
            });
        }
    }

    // Ride request modal
    const driverId=document.getElementById('driver_id');
    const driverInfo=document.getElementById('driverInfo');
    document.querySelectorAll('.request-btn').forEach(btn=>{
        btn.addEventListener('click',()=>{
            driverId.value=btn.dataset.driver;
            driverInfo.innerHTML=`You are requesting <strong>${btn.dataset.name}</strong>`;
            new bootstrap.Modal('#rideModal').show();
        });
    });

    const form=document.getElementById('rideForm');
    const book=document.getElementById('bookBtn');
    form.addEventListener('submit',()=>{
        book.disabled=true;
        book.textContent="Booking...";
    });

    @if(session('success'))
        Swal.fire({icon:'success',title:'Ride Requested',text:'{{ session('success') }}',confirmButtonColor:'#198754'});
    @endif
    @if(session('error'))
        Swal.fire({icon:'error',title:'Error',text:'{{ session('error') }}',confirmButtonColor:'#dc3545'});
    @endif
});
</script>
@endsection
