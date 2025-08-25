@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pay with GCash</h2>

    <p>
        Send your payment to: <strong>09XX-XXX-XXXX</strong><br>
        Account Name: <strong>Your Business Name</strong>
    </p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('passenger.payment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>GCash Number Used</label>
            <input type="text" name="gcash_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Reference Number</label>
            <input type="text" name="reference_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Amount (PHP)</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Upload Screenshot (optional)</label>
            <input type="file" name="screenshot" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
@endsection
