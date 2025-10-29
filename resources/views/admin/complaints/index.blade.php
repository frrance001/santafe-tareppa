@extends('layouts.admin')

@section('content')
<style>
    body {
        background: url('/images/complaints-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
        font-family: 'Inter', sans-serif;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(17, 24, 39, 0.85);
        backdrop-filter: blur(6px);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(14px);
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.6);
        transform: translateY(-3px);
    }

    h1 {
        font-weight: 700;
        color: #facc15;
        text-shadow: 0 2px 10px rgba(250, 204, 21, 0.3);
        letter-spacing: 1px;
    }

    table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    table thead {
        background-color: rgba(255, 255, 255, 0.1);
    }

    table thead th {
        color: #facc15;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table tbody tr {
        background-color: rgba(255, 255, 255, 0.05);
        transition: all 0.25s ease;
    }

    table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: scale(1.01);
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.75em;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-success {
        background-color: #22c55e;
        color: #fff;
    }

    .badge-warning {
        background-color: #facc15;
        color: #1f2937;
    }

    .btn {
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 12px;
        transition: all 0.25s ease;
    }

    .btn-primary {
        background-color: #3b82f6;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-success {
        background-color: #16a34a;
        border: none;
    }

    .btn-success:hover {
        background-color: #15803d;
    }

    .alert {
        border-radius: 10px;
        font-weight: 500;
        backdrop-filter: blur(8px);
    }

    .pagination {
        justify-content: center;
    }

    .pagination .page-link {
        color: #facc15;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .pagination .page-item.active .page-link {
        background-color: #facc15;
        color: #000;
        font-weight: 600;
    }
</style>

<div class="container py-4">
    <div class="text-center mb-5">
        <h1><i class="fas fa-exclamation-circle me-2"></i> Complaints & Reports</h1>
        <p class="text-gray-300 mt-2">Manage and review all user complaints and feedback.</p>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-75 text-white shadow">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Complaints Table --}}
    <div class="glass-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold"><i class="fas fa-list me-2"></i>All Complaints</h5>
            <span class="badge bg-dark px-3 py-2">{{ $complaints->count() }} Records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle">
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
                        <th class="text-center">Action</th>
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
                            <td class="text-center">
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($complaint->status !== 'resolved')
                                    <form action="{{ route('admin.complaints.resolve', $complaint->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-white py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                No complaints found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $complaints->links() }}
        </div>
    </div>
</div>

{{-- FontAwesome Icons --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
