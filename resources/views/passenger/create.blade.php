@extends('layouts.passenger')

@section('content')
<div class="container mt-4">
    <h3>Submit Complaint</h3>
    <form action="{{ route('complaints.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Driver ID (optional)</label>
            <input type="number" name="driver_id" class="form-control">
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <button class="btn btn-danger">Submit Complaint</button>
    </form>
</div>
@endsection
