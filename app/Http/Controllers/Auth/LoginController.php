<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // Check if the user has exceeded max attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($throttleKey); // Reset attempts on success
            $request->session()->regenerate();

            $role = trim(Auth::user()->role);

            if ($role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Driver') {
                return redirect()->route('driver.dashboard');
            } elseif ($role === 'Passenger') {
                return redirect()->route('passenger.dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        // Failed login, increase attempts
        RateLimiter::hit($throttleKey, 60); // 60 seconds lockout

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }
}
