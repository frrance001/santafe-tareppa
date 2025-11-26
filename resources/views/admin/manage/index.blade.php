@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>{{ ucfirst($status) }} Drivers</h2>
    <hr>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Date Registered</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $drivers = $users->where('role', 'Driver'); @endphp

            @forelse ($drivers as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge 
                            @if($user->status === 'approved') bg-success
                            @elseif($user->status === 'pending') bg-warning
                            @else bg-danger
                            @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($user->status === 'pending')
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $user->id }}">Approve</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#disapproveModal{{ $user->id }}">Disapprove</button>
                        @endif
                    </td>
                </tr>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal{{ $user->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveModalLabel{{ $user->id }}">Approve Driver</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to approve <strong>{{ $user->fullname }}</strong>?
                                    <p>Email: {{ $user->email }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Disapprove Modal -->
                <div class="modal fade" id="disapproveModal{{ $user->id }}" tabindex="-1" aria-labelledby="disapproveModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.disapprove', $user->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="disapproveModalLabel{{ $user->id }}">Disapprove Driver</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to disapprove <strong>{{ $user->fullname }}</strong>?
                                    <p>Email: {{ $user->email }}</p>
                                    <div class="mt-3">
                                        <label for="reason{{ $user->id }}" class="form-label">Reason for disapproval (optional)</label>
                                        <textarea name="reason" id="reason{{ $user->id }}" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Disapprove</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center text-danger">No pending drivers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
