@extends('layouts.driver')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Completed Rides & Ratings</h2>

    @if($rides->isEmpty())
        <div class="alert alert-info">No completed rides yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Passenger</th>
                        <th>Pickup Location</th>
                        <th>Drop-off Location</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                        <th>Date Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rides as $ride)
                        <tr>
                            <td>{{ $ride->user->fullname ?? 'N/A' }}</td>
                            <td>{{ $ride->pickup_location }}</td>
                            <td>{{ $ride->dropoff_location }}</td>
                            <td>
                                @if($ride->rating)
                                    {{ $ride->rating }} ⭐
                                @else
                                    <span class="text-muted">Not rated</span>
                                @endif
                            </td>
                            <td>
                                @if($ride->feedback)
                                    {{ $ride->feedback }}
                                @else
                                    <span class="text-muted">No feedback</span>
                                @endif
                            </td>
                            <td>{{ $ride->updated_at ? $ride->updated_at->format('F j, Y - h:i A') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
