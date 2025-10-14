@extends('layouts.passenger')

@section('content')
<style>
    body {
        background: url('/images') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: -1;
    }

    .glass-card {
        background: rgba(238, 242, 243, 0.985);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 15px;
        border: 1px solid rgba(246, 252, 254, 0.93);
        color: #fff;
    }

    .modal-content {
        background: rgba(18, 203, 203, 0.74);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #fff;
        border: 1px solid rgba(45, 166, 209, 0.822);
    }

    h5, p, label {
        color: #fff;
    }

    .btn-success, .btn-danger {
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    h2 {
        color: #ffc107;
        font-weight: bold;
    }

    .badge {
        font-size: 0.9rem;
    }
</style>

<div class="container my-5">
    <h2 class="mb-4">Ride Progress</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!$ride)
        <div class="alert alert-warning">You have no active ride at the moment.</div>
    @else
        <!-- Only show ride status if NOT already rated -->
        @if(!$ride->rating)
            <button type="button" class="w-100 border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#driverModal">
                <div class="card shadow-sm glass-card">
                    <div class="card-body">
                        <h5 class="card-title">Ride Status:
                            <span id="ride-status" class="badge 
                                @if($ride?->status == 'waiting') bg-secondary 
                                @elseif($ride?->status == 'accepted') bg-primary 
                                @elseif($ride?->status == 'in_progress') bg-warning text-dark
                                @elseif($ride?->status == 'completed') bg-success 
                                @elseif($ride?->status == 'cancelled') bg-danger 
                                @endif">
                                {{ ucfirst($ride?->status ?? 'N/A') }}
                            </span>
                        </h5>

                        <hr>

                        <p><strong>Pickup Location:</strong> <span id="pickup">{{ $ride?->pickup_location ?? 'N/A' }}</span></p>
                        <p><strong>Drop-off Location:</strong> <span id="dropoff">{{ $ride?->dropoff_location ?? 'N/A' }}</span></p>
                        <p><strong>Requested At:</strong> <span id="requested">{{ $ride?->created_at?->format('F j, Y - h:i A') ?? 'N/A' }}</span></p>
                    </div>
                </div>
            </button>
        @else
            <div class="alert alert-info">✅ You already rated this ride.</div>
        @endif
    @endif
</div>

<!-- Modal to show driver info -->
<div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="driverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title" id="driverModalLabel">Driver Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="driver-info">
                @if($ride?->driver)
                    <p><strong>Name:</strong> {{ $ride->driver?->fullname ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $ride->driver?->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $ride->driver?->phone ?? 'N/A' }}</p>
                    <p><strong>Assigned At:</strong> {{ $ride->updated_at?->format('F j, Y - h:i A') ?? 'N/A' }}</p>
                @else
                    <p>No driver has been assigned to this ride yet.</p>
                @endif
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <!-- Show rate/report only if completed AND not yet rated -->
                @if($ride?->driver && $ride?->status === 'completed' && !$ride->rating)
                    <div>
                        <a href="{{ route('passenger.rate', $ride->id) }}" class="btn btn-success me-2">Rate Driver</a>
                        <a href="{{ route('passenger.report', $ride->driver_id) }}" class="btn btn-danger">Report Driver</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($ride && !$ride->rating)
<script>
    // Auto-refresh ride status every 5s
    setInterval(() => {
        fetch(`/passenger/ride-status/{{ $ride->id }}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // update badge text + style
                    let badge = document.getElementById("ride-status");
                    if (!badge) return; // stop if already rated
                    badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    badge.className = "badge"; // reset

                    if (data.status === "waiting") badge.classList.add("bg-secondary");
                    else if (data.status === "accepted") badge.classList.add("bg-primary");
                    else if (data.status === "in_progress") badge.classList.add("bg-warning","text-dark");
                    else if (data.status === "completed") badge.classList.add("bg-success");
                    else if (data.status === "cancelled") badge.classList.add("bg-danger");

                    // update driver info if assigned
                    if (data.driver) {
                        document.getElementById("driver-info").innerHTML = `
                            <p><strong>Name:</strong> ${data.driver.fullname ?? 'N/A'}</p>
                            <p><strong>Email:</strong> ${data.driver.email ?? 'N/A'}</p>
                            <p><strong>Phone:</strong> ${data.driver.phone ?? 'N/A'}</p>
                            <p><strong>Assigned At:</strong> ${data.updated_at ?? 'N/A'}</p>
                        `;
                    }
                }
            })
            .catch(err => console.error(err));
    }, 5000);
</script>
@endif
@endsection
