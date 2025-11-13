<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the passenger dashboard with ride history.
     */
    public function index()
    {
        $user = Auth::user();

        // Get passenger rides
        $rides = Ride::where('passenger_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('passenger.dashboard', compact('rides'));
    }

    /**
     * Update passenger location via AJAX.
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Optionally, update the passenger record with latest location
        $user = Auth::user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a ride from passenger history.
     */
    public function destroyRide($id)
    {
        $ride = Ride::where('id', $id)
                    ->where('passenger_id', Auth::id())
                    ->firstOrFail();

        $ride->delete();

        return redirect()->route('passenger.dashboard')
                         ->with('success', 'Ride deleted successfully.');
    }
}
