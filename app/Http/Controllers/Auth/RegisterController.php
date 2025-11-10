<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Validate inputs
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|digits:11',
            'age' => 'required|integer|min:18|max:60',
            'sex' => 'required|in:Male,Female',
            'city' => 'required|string|max:255',
            'role' => 'required|in:driver',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'barangay_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'police_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle file uploads
        $photo = $this->storeFile($request, 'photo', 'uploads/photos');
        $businessPermit = $this->storeFile($request, 'business_permit', 'uploads/docs');
        $barangayClearance = $this->storeFile($request, 'barangay_clearance', 'uploads/docs');
        $policeClearance = $this->storeFile($request, 'police_clearance', 'uploads/docs');

        // Create user with a random password
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
            'city' => $request->city,
            'role' => $request->role,
            'status' => 'pending',
            'photo' => $photo,
            'business_permit' => $businessPermit,
            'barangay_clearance' => $barangayClearance,
            'police_clearance' => $policeClearance,
            'availability' => 'offline',
            'is_verified' => false,
            'is_available' => false,
            'verification_code' => rand(100000, 999999),
            'password' => Hash::make(Str::random(16)),
        ]);

        return redirect()
            ->route('login')
            ->with('success', '✅ Registration successful! You can now log in.');
    }

    /**
     * Store uploaded file with a unique name.
     */
    private function storeFile(Request $request, string $field, string $folder)
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($folder, $filename, 'public');
        }
        return null;
    }
}
