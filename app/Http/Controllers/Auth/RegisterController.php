<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // adjust path if needed
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:11',
            'age' => 'required|integer|min:18|max:60',
            'sex' => 'required|string',
            'role' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'business_permit' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'barangay_clearance' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'police_clearance' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
            'role' => $request->role ?? 'driver',
            'status' => 'pending', // default value
            'photo' => $request->file('photo')?->store('photos', 'public'),
            'business_permit' => $request->file('business_permit')?->store('documents', 'public'),
            'barangay_clearance' => $request->file('barangay_clearance')?->store('documents', 'public'),
            'police_clearance' => $request->file('police_clearance')?->store('documents', 'public'),
            'password' => Hash::make($request->password),
            'availability' => 'offline',
            'is_verified' => false,
            'is_available' => false,
            'verification_code' => rand(100000, 999999),
        ]);

        return redirect()->route('login')->with('status', '✅ Registration successful! Please login.');
    }
}
