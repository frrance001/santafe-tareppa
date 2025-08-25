@extends('layouts.passenger')

@section('content')
<style>
    body {
        background: url('/images/tric.png') no-repeat center center fixed;
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
        <!-- Glass-style card triggers modal -->
        <button type="button" class="w-100 border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#driverModal">
            <div class="card shadow-sm glass-card">
                <div class="card-body">
                    <h5 class="card-title">Ride Status:
                        <span class="badge 
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

                    <p><strong>Pickup Location:</strong> {{ $ride?->pickup_location ?? 'N/A' }}</p>
                    <p><strong>Drop-off Location:</strong> {{ $ride?->dropoff_location ?? 'N/A' }}</p>
                    <p><strong>Requested At:</strong> {{ $ride?->created_at?->format('F j, Y - h:i A') ?? 'N/A' }}</p>
                </div>
            </div>
        </button>
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
            <div class="modal-body">
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

                @if($ride?->driver && $ride?->status === 'completed')
                    <div>
                        <a href="{{ route('passenger.rate', $ride->id) }}" class="btn btn-success me-2">Rate Driver</a>
                        <a href="{{ route('passenger.report', $ride->driver_id) }}" class="btn btn-danger">Report Driver</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
