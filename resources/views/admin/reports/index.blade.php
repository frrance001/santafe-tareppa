@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <h2>Reported Drivers</h2>

    @if($reports->isEmpty())
        <div class="alert alert-info">No reports found.</div>
    @else
        <table class="table table-bordered table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Passenger Name</th>
                    <th>Driver Name</th>
                    <th>Description</th>
                    <th>Date Reported</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $report->passenger->fullname ?? 'N/A' }}</td>
                        <td>{{ $report->driver->fullname ?? 'N/A' }}</td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->created_at->format('F j, Y - h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
