@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h2>{{ $status }} Users</h2>
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
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- CLICKABLE NAME FOR MODAL --}}
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                        {{ $user->fullname }}
                    </a>
                </td>

                <td>{{ $user->email }}</td>
                <td>{{ $user->status }}</td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>

                <td>
                    {{-- APPROVE --}}
                    <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>

                    {{-- DISAPPROVE --}}
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#disapproveModal{{ $user->id }}">
                        Disapprove
                    </button>
                </td>
            </tr>

            {{-- USER DETAILS MODAL --}}
            <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $user->fullname }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                            <p><strong>Address:</strong> {{ $user->address }}</p>
                            <p><strong>Status:</strong> {{ $user->status }}</p>
                            <p><strong>Registered:</strong> {{ $user->created_at }}</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DISAPPROVE MODAL --}}
            <div class="modal fade" id="disapproveModal{{ $user->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('admin.disapprove', $user->id) }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Disapprove {{ $user->fullname }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label">Reason for Disapproval</label>
                            
                            {{-- DROPDOWN VERSION --}}
                            <select name="reason" class="form-select" required>
                                <option value="">Select a reason</option>
                                <option value="Incomplete requirements">Incomplete requirements</option>
                                <option value="Invalid documents">Invalid documents</option>
                                <option value="Failed background check">Failed background check</option>
                                <option value="Not eligible to drive">Not eligible to drive</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>

</div>
@endsection
