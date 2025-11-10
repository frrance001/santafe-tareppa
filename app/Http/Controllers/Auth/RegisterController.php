<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
<<<<<<< HEAD
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    // Show registration form
=======

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

<<<<<<< HEAD
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
=======
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // ✅ Validate input
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
        ]);

        // ✅ Prepare upload paths
        $photoPath = $this->storeFile($request, 'photo', 'photos');
        $businessPermitPath = $this->storeFile($request, 'business_permit', 'documents');
        $barangayClearancePath = $this->storeFile($request, 'barangay_clearance', 'documents');
        $policeClearancePath = $this->storeFile($request, 'police_clearance', 'documents');

        // ✅ Create user
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
<<<<<<< HEAD
            'city' => $request->city,
            'role' => $request->role,
            'photo' => $photo,
            'business_permit' => $businessPermit,
            'barangay_clearance' => $barangayClearance,
            'police_clearance' => $policeClearance,
            'password' => Hash::make(Str::random(16)), // random password
        ]);

       return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');

=======
            'role' => $request->role ?? 'driver',
            'status' => 'pending',
            'photo' => $photoPath,
            'business_permit' => $businessPermitPath,
            'barangay_clearance' => $barangayClearancePath,
            'police_clearance' => $policeClearancePath,
            'availability' => 'offline',
            'is_verified' => false,
            'is_available' => false,
            'verification_code' => rand(100000, 999999),
        ]);

        return redirect()
            ->route('login')
            ->with('status', '✅ Registration successful! Please login.');
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
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
    }
}
