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
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'age' => 'required|numeric|min:1',
            'sex' => 'required|in:Male,Female,Other',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:Driver,Passenger',
        ]);

        // ✅ Save User
        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'sex' => $request->sex,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('status', 'Account created. Please login.');
    }
}
