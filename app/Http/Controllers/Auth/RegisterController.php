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
        'first_name' => 'required|string|max:255',
        'middle_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|digits:11',
        'age' => 'required|integer|min:18',
        'sex' => 'required|in:Male,Female',
        'city' => 'required|string|max:255',
        'role' => 'required|in:driver',
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'business_permit' => 'required|file|mimes:pdf,jpeg,png|max:4096',
        'barangay_clearance' => 'required|file|mimes:pdf,jpeg,png|max:4096',
        'police_clearance' => 'required|file|mimes:pdf,jpeg,png|max:4096',
    ]);

    // Combine name fields into fullname
    $fullname = $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;

    // Handle file uploads
    $photo = $this->storeFile($request, 'photo', 'uploads/photos');
    $businessPermit = $this->storeFile($request, 'business_permit', 'uploads/docs');
    $barangayClearance = $this->storeFile($request, 'barangay_clearance', 'uploads/docs');
    $policeClearance = $this->storeFile($request, 'police_clearance', 'uploads/docs');

    // Create user with random password (for OTP or email login)
    $user = User::create([
        'fullname' => $fullname,
        'email' => $request->email,
        'phone' => $request->phone,
        'age' => $request->age,
        'sex' => $request->sex,
        'city' => $request->city,
        'role' => $request->role,
        'password' => Hash::make(Str::random(10)),
        'photo' => $photo,
        'business_permit' => $businessPermit,
        'barangay_clearance' => $barangayClearance,
        'police_clearance' => $policeClearance,
        'status' => 'pending', // mark pending for admin approval
    ]);

    return redirect()
        ->route('login')
        ->with('success', '✅ Registration submitted! Please wait for admin approval.');
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
