<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    // Show payment form
    public function create()
    {
        return view('passenger.payment.create');
    }

    // Handle payment submission
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|in:gcash,paymaya',
            'amount' => 'required|numeric|min:1',
        ]);

        Payment::create([
            'user_id' => auth()->id(),
            'method'  => $request->method,
            'amount'  => $request->amount,
            'status'  => 'pending',
        ]);

        return redirect()->route('passenger.payment.create')->with('success', 'Payment initialized.');
    }
}
