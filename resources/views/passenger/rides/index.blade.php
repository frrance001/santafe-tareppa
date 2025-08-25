@extends('layouts.passenger')

@section('content')
    <h2>My Ride Requests</h2>

    @if($rides->isEmpty())
        <p>You have not requested any rides yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pickup</th>
                    <th>Drop-off</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rides as $ride)
                    <tr>
                        <td>{{ $ride->pickup_location }}</td>
                        <td>{{ $ride->dropoff_location }}</td>
                        <td>{{ ucfirst($ride->status) }}</td>
                        <td>{{ $ride->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
