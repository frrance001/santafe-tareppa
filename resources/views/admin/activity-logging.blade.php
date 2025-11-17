@extends('layouts.admin')

@section('content')
<h1>Activity Logging - Users</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Role</th>
            <th>Status</th>
            <th>Business Permit</th>
            <th>Barangay Clearance</th>
            <th>Police Clearance</th>
            <th>City</th>
            <th>Password (hashed)</th>
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
            <td>{{ $user->status }}</td>
            <td>{{ $user->business_permit }}</td>
            <td>{{ $user->barangay_clearance }}</td>
            <td>{{ $user->police_clearance }}</td>
            <td>{{ $user->city }}</td>
            <td>{{ $user->password }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
