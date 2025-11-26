@extends('layouts.admin')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #f5f5f5;
        color: #000;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
        color: #000;
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .glass-card table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0;
    }

    .glass-card table th,
    .glass-card table td {
        padding: 12px 15px;
        text-align: left;
        font-size: 0.95rem;
        color: #000;
        border-bottom: 1px solid #e0e0e0;
    }

    .glass-card table th {
        background-color: #f0f0f0;
        font-weight: 700;
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
        transition: all 0.2s ease;
    }

    h1 {
        color: #111;
        font-weight: 700;
        margin-bottom: 30px;
    }

    h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .alert {
        border-radius: 10px;
        font-weight: 600;
        text-align: center;
    }

    .score-badge {
        padding: 4px 10px;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
    }
    .score-low { background-color: #f87171; }
    .score-medium { background-color: #fbbf24; }
    .score-high { background-color: #22c55e; }

    @media (max-width: 768px) {
        .glass-card table th,
        .glass-card table td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container py-5">
    <h1 class="text-center">Ratings & Feedback</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="glass-card">

        {{-- SEARCH BOX --}}
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by passenger, driver, or comment">
        </div>

        <h5>All Ratings</h5>

        <div class="table-responsive">
            <table class="table table-hover" id="ratingsTable">
                <thead>
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

                            {{-- Passenger (from Ride only) --}}
                            <td>
                                @php
                                    $passenger = optional($rating->rateable->passenger);
                                @endphp
                                {{ $passenger->email ?? 'Unknown Email' }}
                            </td>

                            {{-- Driver (from Ride only) --}}
                            <td>
                                @php
                                    $driver = optional($rating->rateable->driver);
                                @endphp
                                {{ $driver->email ?? 'Unknown Email' }}
                            </td>

                            {{-- Score --}}
                            <td>
                                @php
                                    $scoreClass = 'score-low';
                                    if ($rating->score >= 3 && $rating->score < 5) $scoreClass = 'score-medium';
                                    if ($rating->score == 5) $scoreClass = 'score-high';
                                @endphp
                                <span class="score-badge {{ $scoreClass }}">{{ $rating->score }}</span>
                            </td>

                            <td>{{ Str::limit($rating->comment, 50) }}</td>
                            <td>{{ $rating->rateable_id }}</td>
                            <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No ratings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $ratings->links() }}
        </div>
    </div>
</div>

{{-- CLIENT-SIDE SEARCH SCRIPT --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('ratingsTable').getElementsByTagName('tbody')[0];
    const rows = table.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();

        for (let i = 0; i < rows.length; i++) {
            let rowText = rows[i].textContent.toLowerCase();
            rows[i].style.display = rowText.includes(filter) ? '' : 'none';
        }
    });
</script>

@endsection
