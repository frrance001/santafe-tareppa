<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
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
    }
}
