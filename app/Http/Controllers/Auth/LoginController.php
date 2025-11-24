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
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    // ==========================
    // SHOW LOGIN PAGE
    // ==========================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ==========================
    // MAIN LOGIN
    // ==========================
    public function login(Request $request)
    {
        $request->validate([
            'role'  => 'required|string',
            'email' => 'required|email',
        ]);

        $role  = ucfirst(strtolower($request->role));
        $email = strtolower($request->email);

        // Rate limiting
        $throttleKey = $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds."
            ]);
        }

        // reCAPTCHA v3 VALIDATION
$token = $request->recaptcha_token;
if (!$token) {
    return back()->with('error', 'Missing reCAPTCHA token.');
}

$response = Http::asForm()->post(
    'https://www.google.com/recaptcha/api/siteverify',
    [
        'secret'   => env('RECAPTCHA_SECRET'),
        'response' => $token,
        'remoteip' => $request->ip(),
    ]
)->json();

// Verify success, score, and action
if (!($response['success'] ?? false) 
    || ($response['score'] ?? 0) < 0.5 
    || ($response['action'] ?? '') !== 'login') {
    RateLimiter::hit($throttleKey, 60);
    return back()->with('error', 'reCAPTCHA verification failed.');
}

        // ======================================
        // ADMIN LOGIN (PASSWORD LOGIN)
        // ======================================
        if ($role === 'Admin') {
            $request->validate(['password' => 'required|string']);

            $user = User::where('email', $email)->where('role', 'Admin')->first();

            if (!$user) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Admin account does not exist.');
            }

            if (!Hash::check($request->password, $user->password)) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Incorrect password.');
            }

            Auth::login($user);
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // ======================================
        // DRIVER & PASSENGER LOGIN (OTP LOGIN)
        // ======================================
        $user = User::where('email', $email)->where('role', $role)->first();

        if ($role === 'Driver') {
            if (!$user) {
                RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'Driver account does not exist.');
            }

            if ($user->status === 'pending') {
                return back()->with('error', 'Your account is still pending approval.');
            }

            if ($user->status === 'disapproved') {
                return back()->with('error', 'Your registration was disapproved.');
            }
        }

        // ======================================
        // GENERATE OTP
        // ======================================
        $otp = rand(100000, 999999);

        Session::put([
            'otp'        => $otp,
            'otp_email'  => $email,
            'otp_role'   => $role,
            'otp_expires'=> Carbon::now()->addMinutes(5),
        ]);

        // EMAIL OTP
        Mail::raw("Your OTP Code is: $otp\nValid for 5 minutes.", function ($msg) use ($email, $role) {
            $msg->to($email)->subject("Santafe Tareppa $role Login OTP");
        });

        // Count as login attempt
        RateLimiter::hit($throttleKey, 60);

        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
    }

    // ==========================
    // SHOW OTP PAGE
    // ==========================
    public function showOtpForm()
    {
        if (!Session::has('otp_email')) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        return view('auth.verify-otp');
    }

    // ==========================
    // VERIFY OTP
    // ==========================
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $email   = Session::get('otp_email');
        $role    = Session::get('otp_role');
        $expires = Session::get('otp_expires');

        $throttleKey = $email . '|' . $request->ip();

        // Check expiration
        if (!$expires || Carbon::now()->greaterThan($expires)) {
            Session::forget(['otp', 'otp_email', 'otp_role', 'otp_expires']);
            return back()->with('error', 'OTP expired. Please login again.');
        }

        // Check OTP match
        if ($request->otp != Session::get('otp')) {
            RateLimiter::hit($throttleKey, 60);
            return back()->with('error', 'Invalid OTP.');
        }

        RateLimiter::clear($throttleKey);

        // ==========================
        // DRIVER LOGIN
        // ==========================
        if ($role === 'Driver') {
            $user = User::where('email', $email)
                        ->where('role', 'Driver')
                        ->first();

            if (!$user || $user->status !== 'approved') {
                return redirect()->route('login')->with('error', 'Your account is not approved.');
            }

            Auth::login($user);
        }

        // ==========================
        // PASSENGER LOGIN (AUTO-CREATE)
        // ==========================
        if ($role === 'Passenger') {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role'      => 'Passenger',
                    'fullname'  => 'Passenger User',
                    'password'  => bcrypt(Str::random(10)),
                ]
            );

            Auth::login($user);
        }

        // Clear OTP data
        Session::forget(['otp', 'otp_email', 'otp_role', 'otp_expires']);

        return $role === 'Driver'
            ? redirect()->route('driver.dashboard')
            : redirect()->route('passenger.dashboard');
    }
}
