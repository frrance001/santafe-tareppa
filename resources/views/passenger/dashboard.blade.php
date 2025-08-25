@extends('layouts.passenger')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');

        body {
            background: url('/images/tric.png') no-repeat center center fixed;
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
            background: rgba(113, 93, 97, 0.75);
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
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(227, 170, 14, 0.185);
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .table {
            background-color: rgba(232, 27, 27, 0.716);
            color: #fff;
        }

        .table th, .table td {
            border-color: #c81560;
        }

        .alert {
            background-color: rgba(218, 23, 72, 0.237);
            color: #fff;
            border-color: #f20e8f;
        }

        /* Leaflet Map */
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
        <h1 class="animated-welcome text-center"> Welcome Back, Passenger!</h1>

        {{-- GPS Map --}}
        <div class="glass-box mb-5">
            <h3 class="mb-3">📍 Your Location</h3>
            <div id="map"></div>
        </div>

        {{-- Ride History --}}
        <div class="glass-box">
            <h3 class="mb-4">🕒 Ride History</h3>
            @if($rides->isEmpty())
                <div class="alert alert-info">No rides found.</div>
            @else
                <table class="table table-bordered">
                    <thead>
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
                                <td>{{ $ride->created_at->format('M d, Y h:i A') }}</td>
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
            // Initialize map
            var map = L.map('map').setView([11.1951, 123.6929], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([11.1951, 123.6929]).addTo(map)
                .bindPopup("📍 You are here")
                .openPopup();

            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]).bindPopup("📍 Your Location").openPopup();
                }, function () {
                    alert("⚠️ GPS access denied. Showing default location.");
                });
            } else {
                alert("❌ Your browser doesn't support GPS.");
            }

            // Handle delete ride
            const deleteButtons = document.querySelectorAll('.delete-ride');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const rideId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This ride history will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/passenger/ride/${rideId}`;

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // SweetAlert success message with redirect
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    // Redirect to dashboard
                    window.location.href = "{{ route('passenger.dashboard') }}";
                });
            @endif
        });
    </script>
@endsection
