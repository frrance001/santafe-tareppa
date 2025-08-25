@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1>Complaints & Reports</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mt-4">
        <div class="card-header">All Complaints</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
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
                            <td>{{ \Illuminate\Support\Str::limit($complaint->message, 50) }}</td>
                            <td>
                                <span class="badge 
                                    {{ $complaint->status === 'resolved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary">View</a>
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
                            <td colspan="6" class="text-center">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
