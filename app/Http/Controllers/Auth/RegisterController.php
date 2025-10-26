<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Validate inputs (no password required)
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:11',
            'age' => 'required|integer|min:18',
            'sex' => 'required|in:Male,Female',
            'city' => 'required|string|max:255',
            'role' => 'required|in:driver',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'barangay_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'police_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle file uploads
        $photo = $request->file('photo')->store('uploads/photos', 'public');
        $businessPermit = $request->file('business_permit')->store('uploads/docs', 'public');
        $barangayClearance = $request->file('barangay_clearance')->store('uploads/docs', 'public');
        $policeClearance = $request->file('police_clearance')->store('uploads/docs', 'public');

        // Create user (password is random, hashed)
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
            'city' => $request->city,
            'role' => $request->role,
            'photo' => $photo,
            'business_permit' => $businessPermit,
            'barangay_clearance' => $barangayClearance,
            'police_clearance' => $policeClearance,
            'password' => Hash::make(Str::random(16)), // random password
        ]);

       return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');

    }
}
