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
    <h1 class="text-center mb-4">📩 Complaints & Reports</h1>

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
                        <th>User</th>
                        <th>Message</th>
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
                            <td>{{ Str::limit($complaint->message, 50) }}</td>
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
                            <td colspan="6" class="text-center text-white">No complaints found.</td>
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
@endsection
