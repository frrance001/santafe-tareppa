@extends('layouts.admin')

@section('content')

<!-- Google Font -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #f5f5f5;
        color: #000;
    }

    .glass-card {
        background: rgba(200, 176, 176, 0.95);
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
        background: #e3d5d5;
        border-radius: 12px;
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
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
        color: #dfa4a4;
        font-weight: 600;
    }

    .score-low { background-color: #f87171; }
    .score-medium { background-color: #fbbf24; }
    .score-high { background-color: #22c55e; }

    /* Glass-style inputs */
    input.form-control {
        background: rgba(24, 23, 23, 0.1);
        color: #000;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 8px 12px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    input.form-control:focus {
        background: rgba(22, 22, 22, 0.05);
        outline: none;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);
        transform: translateY(-1px);
    }

    /* Comment preview */
    td.comment-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        position: relative;
    }

    td.comment-preview:hover::after {
        content: attr(data-full);
        position: absolute;
        top: 25px;
        left: 0;
        background: rgba(0,0,0,0.85);
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
        white-space: normal;
        z-index: 1000;
        width: 250px;
    }

    @media (max-width: 768px) {
        .glass-card table th,
        .glass-card table td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }

        input.form-control {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container py-5">
    <h1 class="text-center">Ratings & Feedback</h1>

    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="glass-card">

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
                            <td>{{ optional($rating->rateable->passenger)->email ?? 'Unknown Email' }}</td>
                            <td>{{ optional($rating->rateable->driver)->email ?? 'Unknown Email' }}</td>
                            <td>{{ $rating->score }}</td>
                            <td class="comment-preview" data-full="{{ $rating->comment }}">{{ $rating->comment }}</td>
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
    </div>
</div>

<!-- jQuery & DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#ratingsTable').DataTable({
            "pageLength": 25,
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": [4] } // Disable sorting on comment
            ]
        });
    });
</script>

@endsection
