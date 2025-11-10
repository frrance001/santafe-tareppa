<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class OtpLoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|ends_with:@gmail.com',
            'role' => 'required|in:driver,passenger'
        ]);

        $email = $request->email;
        $otp = rand(100000, 999999);

        // ✅ Save OTP in session (you can also store in DB if you prefer)
        Session::put('otp', $otp);
        Session::put('email', $email);
        Session::put('role', $request->role);

        try {
            // Example only — you can configure a proper Mail setup
            Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
                $message->to($email)->subject('Your OTP Code');
            });

            return response()->json(['success' => true, 'message' => 'OTP sent to your Gmail.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP: ' . $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $storedOtp = Session::get('otp');
        $email = Session::get('email');
        $role = Session::get('role');

        if ($request->otp == $storedOtp) {
            Session::forget('otp');

            // ✅ Optional: Auto-login user if exists
            $user = User::where('email', $email)->where('role', $role)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            auth()->login($user);

            // Redirect by role
            $redirect = $role === 'driver'
                ? route('driver.dashboard')
                : route('passenger.dashboard');

            return response()->json(['success' => true, 'redirect' => $redirect]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP'], 400);
    }
}
