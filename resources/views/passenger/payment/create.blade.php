@extends('layouts.passenger')

@section('content')
<style>
    .payment-container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 20px;
    }

    .payment-option {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .payment-option img {
        height: 40px;
        margin-bottom: 10px;
    }

    .payment-option:hover {
        border-color: #007bff;
        box-shadow: 0 5px 15px rgba(0,123,255,0.2);
    }

    .payment-option input {
        display: none;
    }

    .payment-option.active {
        border-color: #007bff;
        background: #f0f8ff;
    }

    .submit-btn {
        margin-top: 25px;
        width: 100%;
    }
</style>

<div class="payment-container">
    <div class="payment-header">
        <h3>Choose Payment Method</h3>
        <p>Pay for Ride #{{ $ride->id ?? 'N/A' }} | Fare: ₱{{ number_format($ride->fare ?? 0, 2) }}</p>
    </div>

    <form method="POST" action="{{ route('passenger.payment.store', $ride->id ?? 0) }}">
        @csrf
        <div class="payment-methods">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="gcash" required>
                <img src="/images/gcash.png" alt="GCash">
                <div>GCash</div>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="paypal">
                <img src="/images/paypal.png" alt="PayPal">
                <div>PayPal</div>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="credit_card">
                <img src="/images/credit.png" alt="Credit/Debit">
                <div>Credit/Debit</div>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="paymaya">
                <img src="/images/paymaya.png" alt="PayMaya">
                <div>PayMaya</div>
            </label>
        </div>

        <button type="submit" class="btn btn-primary submit-btn">Proceed to Payment</button>
    </form>
</div>

<script>
    // Highlight selected payment option
    document.querySelectorAll('.payment-option input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            this.closest('.payment-option').classList.add('active');
        });
    });
</script> @endsection 