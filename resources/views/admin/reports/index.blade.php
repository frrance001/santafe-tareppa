@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <h2>All Passenger Reports</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Passenger</th>
                <th>Driver</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->passenger->fullname }}</td>
                <td>{{ $report->driver->fullname }}</td>
                <td>{{ $report->description }}</td>
                <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
