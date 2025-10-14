@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Complaint #{{ $complaint->id }}</h3>

    <div class="mb-3">
        <strong>Passenger:</strong> {{ optional($complaint->passenger)->name ?? '—' }} <br>
        <strong>Driver:</strong> {{ optional($complaint->driver)->name ?? '—' }} <br>
        <strong>Status:</strong> {{ ucfirst($complaint->status) }} <br>
        <strong>Message:</strong>
        <p>{{ $complaint->message }}</p>
    </div>

    <form method="post" action="{{ route('admin.complaints.update', $complaint) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control w-auto d-inline-block">
                @foreach(['open','in_review','resolved','closed'] as $s)
                    <option value="{{ $s }}" @selected($complaint->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary btn-sm">Update</button>
        </div>
    </form>

    <hr>

    <h5>Passenger Ratings</h5>
    @if($complaint->passenger)
        @forelse($complaint->passenger->ratingsReceived as $rating)
            <div class="border p-2 mb-2">
                <strong>{{ optional($rating->rater)->name ?? 'User' }}</strong>
                — Score: {{ $rating->score }} / 5
                <div>{{ $rating->comment }}</div>
                <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <div>No ratings for this passenger yet.</div>
        @endforelse
    @else
        <div>Passenger not found.</div>
    @endif

    <hr>

    <h5>Driver Ratings</h5>
    @if($complaint->driver)
        @forelse($complaint->driver->ratingsReceived as $rating)
            <div class="border p-2 mb-2">
                <strong>{{ optional($rating->rater)->name ?? 'User' }}</strong>
                — Score: {{ $rating->score }} / 5
                <div>{{ $rating->comment }}</div>
                <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <div>No ratings for this driver yet.</div>
        @endforelse
    @else
        <div>Driver not found.</div>
    @endif
</div>
@endsection
