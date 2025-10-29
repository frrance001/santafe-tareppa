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
</style>

<div class="payment-container">
    <div class="payment-header">
        <h3>Choose Payment Method</h3>
        <p>Pay for Ride #{{ $ride->id ?? 'N/A' }} | Fare: ₱{{ number_format($ride->fare ?? 0, 2) }}</p>
    </div>

    <div class="payment-methods">
        <div class="payment-option" data-url="https://www.gcash.com/">
            <img src="/images/gcash.png" alt="GCash">
            <div>GCash</div>
        </div>

        <div class="payment-option" data-url="https://www.paypal.com/">
            <img src="/images/paypal.png" alt="PayPal">
            <div>PayPal</div>
        </div>

        <div class="payment-option" data-url="https://www.visa.com/payments/">
            <img src="/images/credit.png" alt="Credit/Debit">
            <div>Credit/Debit</div>
        </div>

        <div class="payment-option" data-url="https://www.paymaya.com/">
            <img src="/images/paymaya.png" alt="PayMaya">
            <div>PayMaya</div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            // Highlight selected option
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');

            // Redirect to payment link
            const url = this.dataset.url;
            if(url) {
                window.location.href = url;
            }
        });
    });
</script>
@endsection
