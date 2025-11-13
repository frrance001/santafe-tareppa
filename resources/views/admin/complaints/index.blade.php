@extends('layouts.admin')

@section('content')
<style>
    body {
        background: url('/images/complaints-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        color: #fff;
    }

    .glass-card table th,
    .glass-card table td {
        background-color: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .badge-success {
        background-color: #22c55e;
    }

    .badge-warning {
        background-color: #facc15;
        color: #1f2937;
    }

    h1 {
        color: #facc15;
        font-weight: bold;
    }

    .btn-primary, .btn-success {
        font-weight: 600;
        border: none;
    }

    .alert {
        border-radius: 10px;
    }
</style>

<div class="container py-4">
    <h1 class="text-center mb-4">Ratings & Feedback</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-75 text-white">{{ session('success') }}</div>
    @endif

    <div class="glass-card mt-4">
        <h5 class="mb-3">All Ratings</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover rounded text-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Passenger</th>
                        <th>Driver</th>
                        <th>Score</th>
                        <th>Comment</th>
                        <th>Rated Ride ID</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->rater->name ?? 'Unknown' }}</td>
                            <td>
                                @if($rating->rateable_type === App\Models\Ride::class)
                                    {{ $rating->rater->name ?? 'Unknown' }}

                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $rating->score }}</td>
                            <td>{{ Str::limit($rating->comment, 50) }}</td>
                            <td>{{ $rating->rateable_id }}</td>
                            <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white">No ratings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 text-white">
            {{ $ratings->links() }}
        </div>
    </div>
</div>
@endsection
