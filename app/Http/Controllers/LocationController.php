<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LocationController extends Controller
{
    // Fetch a single user's location by email
    public function fetchByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->latitude && $user->longitude) {
            return response()->json([
                'email' => $user->email,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
            ]);
        }

        return response()->json(['error' => 'User not found or location not set'], 404);
    }
}
