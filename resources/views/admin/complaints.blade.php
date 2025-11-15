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
    <h1 class="text-center mb-4"> Complaints & Reports</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-75 text-white">{{ session('success') }}</div>
    @endif

    {{-- Complaints Table --}}
    <div class="glass-card mt-4">
        <h5 class="mb-3">All Complaints</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover rounded text-white">
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
                            <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary mb-1">View</a>
                                @if($complaint->status !== 'resolved')
                                    <form action="{{ route('admin.complaints.resolve', $complaint->id) }}" method="POST" class="d-inline">
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

        {{-- Pagination --}}
        <div class="mt-3 text-white">
            {{ $complaints->links() }}
        </div>
    </div>
</div>
@endsection@extends('layouts.admin')

@section('content')
<style>
    body {
        background: url('/images/complaints-bg.jpg') no-repeat center center fixed;
        background-size: cover;
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
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        color: #fff;
        transition: transform 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .glass-card table {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    .glass-card table th,
    .glass-card table td {
        padding: 12px 15px;
        text-align: center;
        color: #fff;
    }

    .glass-card table th {
        background: rgba(255, 255, 255, 0.15);
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .badge-success {
        background-color: #22c55e;
        color: #fff;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 12px;
    }

    .badge-warning {
        background-color: #facc15;
        color: #1f2937;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 12px;
    }

    h1 {
        color: #facc15;
        font-weight: bold;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    .btn-primary, .btn-success {
        font-weight: 600;
        border-radius: 10px;
        padding: 6px 12px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-success:hover {
        background-color: #16a34a;
    }

    .alert {
        border-radius: 12px;
        font-weight: 500;
        text-align: center;
    }

    /* Responsive Table */
    @media (max-width: 768px) {
        .glass-card table th,
        .glass-card table td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
        .btn-primary, .btn-success {
            padding: 4px 8px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="container py-5">
    <h1 class="text-center mb-5">🚨 Complaints & Reports</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-75 text-white mb-4">{{ session('success') }}</div>
    @endif

    {{-- Complaints Table --}}
    <div class="glass-card">
        <h5 class="mb-4 text-yellow-300">All Complaints</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-white">
                <thead>
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
                            <td>{{ $complaint->rating?->comment ?? 'No rating' }}</td>
                            <td>
                                <span class="badge {{ $complaint->status === 'resolved' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                            <td class="d-flex justify-content-center gap-1 flex-wrap">
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary mb-1">View</a>
                                @if($complaint->status !== 'resolved')
                                    <form action="{{ route('admin.complaints.resolve', $complaint->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success mb-1">Resolve</button>
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

        {{-- Pagination --}}
        <div class="mt-4 text-white d-flex justify-content-center">
            {{ $complaints->links() }}
        </div>
    </div>
</div>
@endsection

