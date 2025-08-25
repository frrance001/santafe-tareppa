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

    .glass-container {
        background: rgba(174, 167, 167, 0.875);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 30px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        color: #fff;
    }

    h2 {
        color: #ffc107;
        font-weight: bold;
    }

    .list-group-item {
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.2);
        margin-bottom: 10px;
    }

    .alert {
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
    }
</style>

<div class="container mt-5">
    <div class="glass-container">
        <h2>🕒 Waiting Rides</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($rides->isEmpty())
            <div class="alert alert-info">No waiting rides found.</div>
        @else
            <ul class="list-group">
                @foreach($rides as $ride)
                    <li class="list-group-item">
                        <strong>Pickup:</strong> {{ $ride->pickup_location }}<br>
                        <strong>Drop-off:</strong> {{ $ride->dropoff_location }}<br>
                        <strong>Status:</strong> {{ ucfirst($ride->status) }}<br>
                        <strong>Phone:</strong> {{ $ride->phone_number ?? 'N/A' }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
