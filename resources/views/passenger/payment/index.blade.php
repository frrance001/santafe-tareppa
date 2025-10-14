@extends('layouts.passenger')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">💳 Choose Payment Method</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('payment.pay') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Enter Amount (₱)</label>
            <input type="number" name="amount" id="amount" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label class="form-label">Select Payment Method</label><br>
            <button type="submit" name="method" value="gcash" class="btn btn-primary">
                <img src="/images/gcash.png" alt="GCash" width="80"> Pay with GCash
            </button>
            <button type="submit" name="method" value="paymaya" class="btn btn-success">
                <img src="/images/paymaya.png" alt="PayMaya" width="80"> Pay with PayMaya
            </button>
        </div>
    </form>
</div>
@endsection
