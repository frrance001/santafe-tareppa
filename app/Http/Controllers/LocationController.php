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

    // ✅ Add this update() method to fix your HTTP 500 error
    public function update(Request $request, $id)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->latitude  = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json([
            'message'   => 'Location updated successfully',
            'latitude'  => $user->latitude,
            'longitude' => $user->longitude,
        ]);
    }
}
