@extends('layouts.passenger')

@section('content')
<style>
    body {
        background: url('/images') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #fff;
        font-family: 'Poppins', sans-serif;
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
        border-radius: 15px;
        border: 1px solid rgba(246, 252, 254, 0.93);
        color: #000;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-3px);
    }

    .modal-content {
        background: rgba(18, 203, 203, 0.85);
        backdrop-filter: blur(10px);
        color: #fff;
        border-radius: 12px;
        border: 1px solid rgba(45, 166, 209, 0.822);
    }

    h2 {
        color: #ffc107;
        font-weight: bold;
        text-align: center;
    }

    h5, p, label {
        color: #000;
    }

    .badge {
        font-size: 0.9rem;
    }

    .btn-success, .btn-danger, .btn-secondary {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .alert {
        border-radius: 10px;
    }

    /* ==============================
       📱 RESPONSIVE DESIGN SECTION
       ============================== */
    @media (max-width: 992px) {
        h2 {
            font-size: 1.8rem;
        }
        .glass-card {
            padding: 1rem;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        h2 {
            font-size: 1.6rem;
        }

        .glass-card {
            padding: 1rem;
            font-size: 0.95rem;
        }

        .modal-dialog {
            max-width: 90%;
        }

        .modal-body p {
            font-size: 0.9rem;
        }

        .modal-footer {
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 1.4rem;
        }

        .glass-card {
            padding: 0.8rem;
            border-radius: 10px;
        }

        .card-title {
            font-size: 1rem;
        }

        .modal-body p {
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        p, strong, span {
            display: block;
            word-break: break-word;
        }
    }
</style>

<div class="container my-5">
    <h2 class="mb-4">Ride Progress</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(!$ride)
        <div class="alert alert-warning text-center">You have no active ride at the moment.</div>
    @else
        <!-- Only show ride status if NOT already rated -->
        @if(!$ride->rating)
            <button type="button" class="w-100 border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#driverModal">
                <div class="card shadow-sm glass-card">
                    <div class="card-body text-center text-md-start">
                        <h5 class="card-title mb-3">Ride Status:
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

                        <p><strong>Pickup Location:</strong> <span id="pickup">{{ $ride?->pickup_location ?? 'N/A' }}</span></p>
                        <p><strong>Drop-off Location:</strong> <span id="dropoff">{{ $ride?->dropoff_location ?? 'N/A' }}</span></p>
                        <p><strong>Requested At:</strong> <span id="requested">{{ $ride?->created_at?->format('F j, Y - h:i A') ?? 'N/A' }}</span></p>
                    </div>
                </div>
            </button>
        @else
            <div class="alert alert-info text-center">✅ You already rated this ride.</div>
        @endif
    @endif
</div>

<!-- Modal to show driver info -->
<div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="driverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
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
            <div class="modal-footer border-0 d-flex justify-content-between flex-wrap">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                @if($ride?->driver && $ride?->status === 'completed' && !$ride->rating)
                    <div class="w-100 w-md-auto d-flex flex-column flex-md-row gap-2">
                        <a href="{{ route('passenger.rate', $ride->id) }}" class="btn btn-success flex-fill">Rate Driver</a>
                        @if(auth()->check() && auth()->user()->role === 'passenger')
                            <a href="{{ route('passenger.report', ['driver' => $ride->driver->id]) }}" class="btn btn-danger flex-fill">Report Driver</a>
                        @endif
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
                    let badge = document.getElementById("ride-status");
                    if (!badge) return;

                    badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    badge.className = "badge";

                    const colors = {
                        waiting: "bg-secondary",
                        accepted: "bg-primary",
                        in_progress: "bg-warning text-dark",
                        completed: "bg-success",
                        cancelled: "bg-danger"
                    };
                    badge.className = `badge ${colors[data.status] || 'bg-secondary'}`;

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
