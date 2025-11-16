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
            'role' => 'required',
            'email' => 'required|email',
        ]);

        $role = ucfirst(strtolower($request->input('role')));
        $email = $request->input('email');

        // Throttle login attempts
        $throttleKey = Str::lower($email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // ✅ ADMIN LOGIN (with password)
        if ($role === 'Admin') {
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

        // ✅ DRIVER or PASSENGER LOGIN (OTP only — no user record needed initially)
        // First, check if driver exists and is approved
        if ($role === 'Driver') {
            $user = User::where('email', $email)->where('role', 'Driver')->first();
            if ($user && $user->status !== 'approved') {
                return back()->with('error', 'Your account is not yet approved by the admin.');
            }
        }

        $otp = rand(100000, 999999);

        // Store OTP & login info in session
        Session::put('otp', $otp);
        Session::put('otp_email', $email);
        Session::put('otp_role', $role);

        // Send OTP email
        Mail::raw("Your OTP code is: $otp\n\nValid for 5 minutes.", function ($message) use ($email, $role) {
            $message->to($email)
                    ->subject("Santafe Tareppa $role Login OTP");
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

        if ($request->otp == Session::get('otp')) {
            $email = Session::get('otp_email');
            $role = Session::get('otp_role');

            // ✅ Create user with only email, role, and password
            //    No fullname or other fields needed
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role' => $role,
                    'password' => bcrypt(Str::random(10)),
                    'fullname' => $role . ' User', // default fullname
                ]
            );

            Auth::login($user);
            Session::forget(['otp', 'otp_email', 'otp_role']);

            if ($role === 'Passenger') {
                return redirect()->route('passenger.dashboard');
            } elseif ($role === 'Driver') {
                // Check approval again just in case
                if ($user->status !== 'approved') {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Your account is not yet approved by the admin.');
                }
                return redirect()->route('driver.dashboard');
            }
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }
}
