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
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #fff;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
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

    /* Datatables Custom Theme */
    .dataTables_wrapper .dataTables_filter input {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid #555;
        color: #fff !important;
        border-radius: 6px;
    }

    .dataTables_wrapper .dataTables_length select {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid #555;
        color: #fff;
        border-radius: 6px;
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: #fff !important;
    }

    .table thead th {
        background: rgba(0, 0, 0, 0.6) !important;
        color: #facc15 !important;
    }

    .btn-warning {
        background-color: #facc15 !important;
        border: none;
        color: #000 !important;
    }

    .btn-outline-warning {
        border-color: #facc15 !important;
        color: #facc15 !important;
    }

    .btn-outline-warning:hover {
        background: #facc15 !important;
        color: #000 !important;
    }
</style>

<!-- DATATABLES CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<div class="container py-4">
    <h1 class="text-center mb-4">Complaints & Reports</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-75 text-white">
            {{ session('success') }}
        </div>
    @endif

    {{-- Complaints Table --}}
    <div class="glass-card mt-4">
        <h5 class="mb-3">All Complaints</h5>

        <div class="table-responsive">
            <table id="complaintsTable" class="table table-bordered table-hover rounded text-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Passenger</th>
                        <th>Driver</th>
                        <th>Complaint</th>
                        <th>Rating</th>
                        <th>Rating Comment</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->id }}</td>
                            <td>{{ $complaint->user->name ?? 'Unknown' }}</td>
                            <td>{{ $complaint->driver->name ?? 'Unknown' }}</td>
                            <td>{{ Str::limit($complaint->message, 50) }}</td>
                            <td>{{ $complaint->rating?->score ?? 'N/A' }}</td>
                            <td>{{ $complaint->rating?->comment ?? 'No rating given' }}</td>
                            <td>
                                <span class="badge {{ $complaint->status === 'resolved' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary mb-1">View</a>

                                @if($complaint->status !== 'resolved')
                                    <form action="{{ route('admin.complaints.resolve', $complaint->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Mark Resolved</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-white">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {

    let table = $('#complaintsTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Show All"]
        ],

        dom:
            "<'row mb-3'<'col-md-4'l><'col-md-4 text-center'f><'col-md-4 text-end'B>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'i><'col-md-6'p>>",

        buttons: [
            { extend: "excel", className: "btn btn-warning btn-sm text-dark fw-bold" },
            { extend: "csv", className: "btn btn-warning btn-sm text-dark fw-bold" },
            { extend: "print", className: "btn btn-warning btn-sm text-dark fw-bold" },

            {
                text: "Preview Day",
                className: "btn btn-outline-warning btn-sm",
                action: function () { filterByDate(1); }
            },
            {
                text: "Preview Week",
                className: "btn btn-outline-warning btn-sm",
                action: function () { filterByDate(7); }
            },
            {
                text: "Preview Month",
                className: "btn btn-outline-warning btn-sm",
                action: function () { filterByDate(30); }
            }
        ]
    });

    function filterByDate(days) {
        let today = new Date();

        table.rows().every(function () {
            let dateStr = $(this.node()).find("td:nth-child(8)").text().trim();
            let rowDate = new Date(dateStr.replace(/-/g, "/"));

            let diffDays = (today - rowDate) / (1000 * 60 * 60 * 24);

            this.visible(diffDays <= days);
        });
    }
});
</script>

@endsection
