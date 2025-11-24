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

class LoginController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ======================================================
    // 🚀 MAIN LOGIN FUNCTION
    // ======================================================
    public function login(Request $request)
    {
        // 1️⃣ Validate basic input
        $request->validate([
            'role' => 'required|string',
            'email' => 'required|email',
        ]);

        $role = ucfirst(strtolower($request->input('role')));
        $email = $request->input('email');

        // 2️⃣ Rate limiting
        $throttleKey = Str::lower($email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds."
            ])->onlyInput('email');
        }

        // 3️⃣ Validate Google reCAPTCHA v3
        $recaptchaToken = $request->input('recaptcha_token');
        if (!$recaptchaToken) {
            return back()->with('error', 'reCAPTCHA token is missing.');
        }

        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        $recaptchaData = $recaptchaResponse->json();

        if (
            !isset($recaptchaData['success']) || 
            $recaptchaData['success'] !== true || 
            ($recaptchaData['score'] ?? 0) < 0.5
        ) {
            RateLimiter::hit($throttleKey, 60);
            return back()->with('error', 'reCAPTCHA verification failed. Please try again.');
        }

        // ======================================================
        // 🛑 ADMIN LOGIN (Password Required)
        // ======================================================
        if ($role === 'Admin') {
            $request->validate(['password' => 'required|string']);

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

            // ✅ Successful Admin login
            Auth::login($user);
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // ======================================================
        // 🚗 DRIVER / PASSENGER LOGIN (OTP)
        // ======================================================
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
                return back()->with('error', 'Your registration has been disapproved.');
            }
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        Session::put([
            'otp' => $otp,
            'otp_email' => $email,
            'otp_role' => $role,
        ]);

        // Send OTP via email
        Mail::raw("Your OTP code is: $otp\n\nValid for 5 minutes.", function ($message) use ($email, $role) {
            $message->to($email)->subject("Santafe Tareppa $role Login OTP");
        });

        // Count as a login attempt
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

        // Clear OTP attempts
        RateLimiter::clear($throttleKey);

        // Driver login
        if ($role === 'Driver') {
            $user = User::where('email', $email)->where('role', 'Driver')->first();
            if (!$user || $user->status !== 'approved') {
                return redirect()->route('login')->with('error', 'Your account is not approved.');
            }
            Auth::login($user);
        }

        // Passenger login (auto-create if not exists)
        if ($role === 'Passenger') {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role' => $role,
                    'password' => bcrypt(Str::random(10)),
                    'fullname' => 'Passenger User',
                ]
            );
            Auth::login($user);
        }

        // Clear OTP session
        Session::forget(['otp', 'otp_email', 'otp_role']);

        // Redirect based on role
        return $role === 'Driver' ? redirect()->route('driver.dashboard') : redirect()->route('passenger.dashboard');
    }
}
