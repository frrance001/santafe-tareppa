<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login submission
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,driver,passenger',
            'email' => 'required|email',
        ]);

        $role = strtolower($request->input('role'));
        $email = $request->input('email');

        // Throttle login attempts
        $throttleKey = Str::lower($email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // ADMIN LOGIN (with password)
        if ($role === 'admin') {
            $request->validate(['password' => 'required']);

            if (Auth::attempt([
                'email' => $email,
                'password' => $request->password,
                'role' => 'Admin'
            ])) {
                RateLimiter::clear($throttleKey);
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            RateLimiter::hit($throttleKey, 60);
            return back()->with('error', 'Invalid admin credentials.');
        }

        // DRIVER or PASSENGER LOGIN (OTP)
        $otp = rand(100000, 999999);

        // Store OTP & login info in session
        Session::put('otp', $otp);
        Session::put('otp_email', $email);
        Session::put('otp_role', $role);
        Session::put('otp_created_at', now());

        // Send OTP email
        Mail::raw("Your OTP code is: $otp\n\nValid for 5 minutes.", function ($message) use ($email, $role) {
            $message->to($email)
                    ->subject("Santafe Tareppa " . ucfirst($role) . " Login OTP");
        });

        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
    }

    // Show OTP form
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $otp = Session::get('otp');
        $otpCreated = Session::get('otp_created_at');

        // Check if OTP expired (5 minutes)
        if (!$otp || now()->diffInMinutes($otpCreated) > 5) {
            Session::forget(['otp', 'otp_email', 'otp_role', 'otp_created_at']);
            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        if ($request->otp == $otp) {
            $email = Session::get('otp_email');
            $role = Session::get('otp_role');

            // Create user with minimal required fields
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role' => ucfirst($role),
                    'password' => bcrypt(Str::random(10)),
                    'fullname' => ucfirst($role) . ' User',
                    'age' => 18,              // default
                    'sex' => 'Not Specified', // default
                    'city' => 'Unknown',      // default
                    'status' => 'pending',
                    'availability' => 'offline',
                    'is_verified' => false,
                    'is_available' => false,
                ]
            );

            Auth::login($user);
            Session::forget(['otp', 'otp_email', 'otp_role', 'otp_created_at']);

            if ($role === 'passenger') {
                return redirect()->route('passenger.dashboard');
            } elseif ($role === 'driver') {
                return redirect()->route('driver.dashboard');
            }
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }
}
