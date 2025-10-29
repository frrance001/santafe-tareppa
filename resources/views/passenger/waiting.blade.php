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
        background: rgba(253, 252, 252, 0.926);
        z-index: -1;
    }

    .glass-container {
        background: rgba(174, 167, 167, 0.875);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.903);
        padding: 30px;
        box-shadow: 0 8px 24px rgba(247, 245, 245, 0.845);
        color: #333;
        margin: 0 auto;
        max-width: 900px;
    }

    h2 {
        color: #008cff;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .list-group {
        list-style: none;
        padding: 0;
    }

    .list-group-item {
        background-color: rgba(255, 255, 255, 0.8);
        color: #333;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 15px;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .list-group-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .alert {
        background-color: rgba(0, 140, 255, 0.1);
        border: 1px solid rgba(0, 140, 255, 0.3);
        color: #008cff;
        padding: 12px;
        border-radius: 10px;
        text-align: center;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .glass-container {
            padding: 20px;
            width: 90%;
        }

        .list-group-item {
            font-size: 14px;
            padding: 15px;
        }

        h2 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .glass-container {
            padding: 15px;
        }

        h2 {
            font-size: 1.25rem;
        }

        .list-group-item {
            padding: 12px;
            border-radius: 10px;
        }
    }
</style>

<div class="container mt-5 mb-5 px-3">
    <div class="glass-container">
        <h2>🚖 Waiting Rides</h2>

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
                        <p><strong>Pickup:</strong> {{ $ride->pickup_location }}</p>
                        <p><strong>Drop-off:</strong> {{ $ride->dropoff_location }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($ride->status) }}</p>
                        <p><strong>Phone:</strong> {{ $ride->phone_number ?? 'N/A' }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
