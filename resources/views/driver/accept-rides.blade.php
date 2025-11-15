@extends('layouts.driver')

@section('content')
@if(Auth::user()->role !== 'driver')
    <div class="container d-flex justify-content-center align-items-center" style="height:80vh;">
        <div class="text-center">
            <h3 class="text-danger mb-3">🚫 Access Denied</h3>
            <p>You are not allowed to view this page.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Go Back</a>
        </div>
    </div>
@else
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🚗 Available Ride Requests</h4>
        </div>

        <div class="card-body">
            @if($rides->isEmpty())
                <div class="text-center text-muted py-5">
                    <h5>No ride requests available</h5>
                </div>
            @else
                <div class="list-group" id="rideList">
                    @foreach($rides as $ride)
                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border-0 shadow-sm rounded ride-item"
                             id="ride-{{ $ride->id }}"
                             data-bs-id="{{ $ride->id }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 text-primary">{{ $ride->user->fullname ?? 'N/A' }}</h5>
                                    <p class="mb-1 text-muted small">
                                        <strong>From:</strong> {{ $ride->pickup_location }}<br>
                                        <strong>To:</strong> {{ $ride->dropoff_location }}
                                    </p>
                                </div>
                                <span class="badge bg-info status-badge">{{ ucfirst($ride->status) }}</span>
                            </div>

                            <form class="accept-form mt-3 text-end" data-id="{{ $ride->id }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm px-4 fw-semibold">
                                    Accept Ride
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.accept-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const rideId = this.dataset.id;

            fetch(`/driver/rides/${rideId}/accept`, {  // ✔ FIXED ROUTE
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('✅ Ride Accepted!', data.message, 'success');

                    // Update status badge instantly
                    const badge = document.querySelector(`#ride-${rideId} .status-badge`);
                    badge.textContent = 'In Progress';
                    badge.classList.remove('bg-info');
                    badge.classList.add('bg-warning');

                    // Remove from list
                    const rideItem = document.getElementById(`ride-${rideId}`);
                    rideItem.classList.add('fade-out');
                    setTimeout(() => rideItem.remove(), 400);

                    if (document.querySelectorAll('.ride-item').length === 0) {
                        document.getElementById('rideList').innerHTML =
                            '<div class="text-center text-muted py-5"><h5>No ride requests available</h5></div>';
                    }
                } else {
                    Swal.fire('❌ Failed', data.message || 'Something went wrong.', 'error');
                }
            })
            .catch(() => Swal.fire('❌ Error', 'Could not accept ride.', 'error'));
        });
    });
});
</script>

<style>
.ride-item {
    transition: all 0.3s ease;
    background-color: #ffffff;
}
.ride-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}
.fade-out {
    animation: fadeOut 0.4s forwards;
}
@keyframes fadeOut {
    to { opacity: 0; transform: translateX(-20px); }
}
.btn-success {
    border-radius: 20px;
}
.card-header h4 {
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>
@endif
@endsection
