<?php

// app/Http/Controllers/Passenger/PaymentController.php
namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create()
    {
        return view('passenger.payment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gcash_number' => 'required|string',
            'reference_number' => 'required|string',
            'amount' => 'required|numeric',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payments', 'public');
        }

        Payment::create([
            'user_id' => Auth::id(),
            'gcash_number' => $request->gcash_number,
            'reference_number' => $request->reference_number,
            'amount' => $request->amount,
            'screenshot' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('passenger.payment.create')
                         ->with('success', 'Payment submitted! Please wait for confirmation.');
    }
}
