<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ================================================
    // 🚀 MAIN LOGIN FUNCTION
    // ================================================
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'email' => 'required|email',
        ]);

        $role = ucfirst(strtolower($request->input('role')));
        $email = $request->input('email');

        // ================================================
        // 🔐 Throttle protection
        // ================================================
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // ======================================================
        // 🛑 ADMIN LOGIN (password required)
        // ======================================================
        if ($role === 'Admin') {

            $request->validate(['password' => 'required']);

            $user = User::where('email', $email)
                        ->where('role', 'Admin')
                        ->first();

            if (!$user) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Admin account does not exist.');
            }

            if (!Hash::check($request->password, $user->password)) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Incorrect password.');
            }

            // SUCCESS LOGIN
            Auth::login($user);
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // ======================================================
        // 🚗 DRIVER / PASSENGER LOGIN (OTP LOGIN)
        // ======================================================
        if ($role === 'Driver') {
            $user = User::where('email', $email)->where('role', 'Driver')->first();

            if (!$user) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Driver account does not exist.');
            }

            // 🚫 Block pending or disapproved drivers
            if ($user->status === 'pending') {
                return back()->with('error', 'Your account is still pending approval.');
            }

            if ($user->status === 'disapproved') {
                return back()->with('error', 'Your registration has been disapproved.');
            }
        }

        // ======================================================
        // 📩 Generate & Send OTP
        // ======================================================
        $otp = rand(100000, 999999);

        Session::put('otp', $otp);
        Session::put('otp_email', $email);
        Session::put('otp_role', $role);

        Mail::raw("Your OTP code is: $otp\n\nValid for 5 minutes.", function ($message) use ($email, $role) {
            $message->to($email)->subject("Santafe Tareppa $role Login OTP");
        });

        // Count as a failed login step (not yet authenticated)
        RateLimiter::hit($throttleKey, 60);

        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
    }

    // ======================================================
    // 🔐 SHOW OTP VERIFICATION PAGE
    // ======================================================
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    // ======================================================
    // 🔐 VERIFY OTP
    // ======================================================
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $email = Session::get('otp_email');
        $role  = Session::get('otp_role');
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        if ($request->otp != Session::get('otp')) {
            RateLimiter::hit($throttleKey, 60);
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        // OTP is correct → Clear attempts
        RateLimiter::clear($throttleKey);

        // ================================
        // 🚗 DRIVER LOGIN
        // ================================
        if ($role === 'Driver') {
            $user = User::where('email', $email)->where('role', 'Driver')->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Driver account does not exist.');
            }

            if ($user->status !== 'approved') {
                return redirect()->route('login')->with('error', 'Your account is not yet approved.');
            }

            Auth::login($user);
            Session::forget(['otp', 'otp_email', 'otp_role']);

            return redirect()->route('driver.dashboard');
        }

        // ================================
        // 🧍 PASSENGER LOGIN (AUTO-CREATE)
        // ================================
        if ($role === 'Passenger') {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role' => $role,
                    'password' => bcrypt(Str::random(10)),
                    'fullname' => $role . ' User',
                ]
            );

            Auth::login($user);
            Session::forget(['otp', 'otp_email', 'otp_role']);

            return redirect()->route('passenger.dashboard');
        }
    }
}
