<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ride;

class DashboardController extends Controller
{
    /**
     * Show the passenger dashboard with ride history.
     */
    public function index()
    {
        // Get only rides for the logged-in passenger
        $rides = Ride::where('passenger_id', Auth::id())
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
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

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
        // Make sure passenger can only delete their own rides
        $ride = Ride::where('id', $id)
                    ->where('passenger_id', Auth::id())
                    ->firstOrFail();

        $ride->delete();

        return redirect()->route('passenger.dashboard')
                         ->with('success', 'Ride deleted successfully.');
    }
}
