@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">Activity Logging - Your Account</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="activityLogTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>City</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->age }}</td>
                            <td>{{ $user->sex }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if($user->status === 'approved')
                                    <span class="badge bg-success">{{ ucfirst($user->status) }}</span>
                                @elseif($user->status === 'disapproved')
                                    <span class="badge bg-danger">{{ ucfirst($user->status) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $user->city }}</td>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- DataTables script --}}
@section('scripts')
<script>
    $(document).ready(function() {
        $('#activityLogTable').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 25,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [] }
            ]
        });
    });
</script>
@endsection
