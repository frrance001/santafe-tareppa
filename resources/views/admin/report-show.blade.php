@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Complaint Detail</h2>

    <div class="card mt-3">
        <div class="card-body">
            <p><strong>User:</strong> {{ $complaint->user->name ?? 'Unknown' }}</p>
            <p><strong>Message:</strong><br>{{ $complaint->message }}</p>
            <p><strong>Status:</strong> {{ ucfirst($complaint->status) }}</p>
            <p><strong>Date:</strong> {{ $complaint->created_at->format('Y-m-d H:i') }}</p>

            @if($complaint->status !== 'resolved')
            <form action="{{ route('admin.complaints.resolve', $complaint->id) }}" method="POST" class="mt-3">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Mark as Resolved</button>
            </form>
            @endif

            <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary mt-2">Back</a>
        </div>
    </div>
</div>
@endsection
