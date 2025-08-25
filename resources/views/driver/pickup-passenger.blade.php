@extends('layouts.driver')

@section('content')
<div class="container py-4">
    <h2>Ongoing Ride Assignments</h2>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-4 p-4 text-red-800 bg-red-100 border border-red-300 rounded-md">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($rides->isEmpty())
        <p>No ongoing rides found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pickup Location</th>
                    <th>Drop-off Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rides as $ride)
                    @if(in_array($ride->status, ['accepted', 'in_progress']))
                        <tr class="ride-row"
                            data-passenger-name="{{ $ride->user->fullname ?? 'N/A' }}"
                            data-passenger-email="{{ $ride->user->email ?? 'N/A' }}"
                            data-passenger-phone="{{ $ride->phone_number ?? 'N/A' }}"
                            data-pickup="{{ $ride->pickup_location }}"
                            data-dropoff="{{ $ride->dropoff_location }}"
                            data-status="{{ $ride->status }}">
                            
                            <td>{{ $ride->pickup_location }}</td>
                            <td>{{ $ride->dropoff_location }}</td>
                            <td>
                                <span class="badge 
                                    @if($ride->status === 'accepted') bg-primary 
                                    @elseif($ride->status === 'in_progress') bg-warning 
                                    @endif">
                                    {{ ucfirst($ride->status) }}
                                </span>
                            </td>
                            <td>
                                @if($ride->status === 'accepted')
                                    {{-- In Progress Button --}}
                                    <form action="{{ route('driver.ride.progress', ['ride' => $ride->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">In Progress</button>
                                    </form>
                                @endif

                                @if($ride->status === 'in_progress')
                                    {{-- Complete Button --}}
                                    <form action="{{ route('driver.ride.complete', ['ride' => $ride->id]) }}" method="POST" class="d-inline ms-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Complete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Map Modal --}}
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Passenger Info & Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Passenger Name:</strong> <span id="passengerName"></span></p>
                <p><strong>Passenger Email:</strong> <span id="passengerEmail"></span></p>
                <p><strong>Passenger Phone:</strong> <span id="passengerPhone"></span></p>
                <div id="map" style="height: 500px; width: 100%;" class="rounded shadow"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet CSS/JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('mapModal');
    const modal = new bootstrap.Modal(modalEl);

    const passengerNameSpan = document.getElementById('passengerName');
    const passengerEmailSpan = document.getElementById('passengerEmail');
    const passengerPhoneSpan = document.getElementById('passengerPhone');

    let map, pickupMarker, dropoffMarker;

    document.querySelectorAll('.ride-row').forEach(row => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('form') || e.target.tagName === 'BUTTON') return;

            passengerNameSpan.textContent = row.dataset.passengerName;
            passengerEmailSpan.textContent = row.dataset.passengerEmail;
            passengerPhoneSpan.textContent = row.dataset.passengerPhone;

            const pickup = row.dataset.pickup.split(',');
            const dropoff = row.dataset.dropoff.split(',');
            const status = row.dataset.status;

            modal.show();

            modalEl.addEventListener('shown.bs.modal', function () {
                if (!map) {
                    map = L.map('map').setView([11.2, 123.7], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(map);
                }

                if (pickupMarker) map.removeLayer(pickupMarker);
                if (dropoffMarker) map.removeLayer(dropoffMarker);

                if (status === 'in_progress') {
                    // Show pickup & dropoff markers
                    pickupMarker = L.marker([parseFloat(pickup[0]), parseFloat(pickup[1])])
                        .addTo(map)
                        .bindPopup("Pickup Location")
                        .openPopup();

                    dropoffMarker = L.marker([parseFloat(dropoff[0]), parseFloat(dropoff[1])], {icon: L.icon({iconUrl:'https://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png'})})
                        .addTo(map)
                        .bindPopup("Drop-off Location");

                    const bounds = L.latLngBounds([
                        [parseFloat(pickup[0]), parseFloat(pickup[1])],
                        [parseFloat(dropoff[0]), parseFloat(dropoff[1])]
                    ]);
                    map.fitBounds(bounds, {padding: [50, 50]});
                }

                setTimeout(() => {
                    map.invalidateSize();
                }, 200);
            }, {once: true});
        });
    });
});
</script>
@endsection
