<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    // Show all available ride requests (waiting)
    public function index()
{
    $driverId = Auth::id();

    $rides = Ride::with('user')
        ->where(function ($query) use ($driverId) {
            $query->whereNull('driver_id')               // unassigned rides
                  ->orWhere('driver_id', $driverId);     // rides assigned to this driver
        })
        ->whereIn('status', ['waiting', 'accepted'])     // optional: limit to active rides
        ->orderBy('created_at', 'desc')
        ->get();

    return view('driver.accept-rides', compact('rides'));
}

    public function accept($id)
{
    $ride = Ride::findOrFail($id);

    // Check if it's already assigned to a different driver
    if (!is_null($ride->driver_id) && $ride->driver_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Ride is already assigned to another driver.');
    }

    // Optional: Prevent accepting if not in 'waiting' status
    if ($ride->status !== 'waiting') {
        return redirect()->back()->with('error', 'This ride is no longer available.');
    }

    // Assign to current driver and update status
    $ride->driver_id = Auth::id();
    $ride->status = 'accepted';
    $ride->save();

    return redirect()->route('driver.accept-rides')->with('success', 'Ride accepted successfully.');
}




    // Show accepted rides (for pickup)
    public function pickup()
{
    $rides = Ride::with('user')
        ->where('driver_id', auth()->id())
        ->whereIn('status', ['accepted', 'in_progress'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('driver.pickup-passenger', compact('rides'));
}


    // Mark ride as in progress
    public function markInProgress(Ride $ride)
    {
        if ($ride->driver_id !== auth()->id()) {
            abort(403);
        }

        $ride->status = 'in_progress';
        $ride->save();

        return redirect()->back()->with('success', 'Ride marked as in progress.');
    }

    // Mark ride as completed
    public function markComplete(Ride $ride)
    {
        if ($ride->driver_id !== auth()->id()) {
            abort(403);
        }

        $ride->status = 'completed';
        $ride->save();

        return redirect()->back()->with('success', 'Ride marked as completed.');
    }

    // Show completed rides with ratings
    public function completedRides()
{
    $driverId = auth()->id();

    $completedRides = Ride::where('driver_id', $driverId)
                          ->where('status', 'completed')
                          ->with('passenger')
                          ->orderByDesc('updated_at')
                          ->get();

    return view('driver.completed-rides', ['rides' => $completedRides]);

}

    // Show all rides assigned to the driver
    public function showAssignedRides()
    {
        $rides = Ride::with('user')
            ->where('driver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.rides', compact('rides'));
    }

    // Set driver availability
    public function setAvailability(Request $request)
    {
        $request->validate([
            'availability' => 'required|in:available,not_available',
        ]);

        $driver = Auth::user();
        $driver->is_available = $request->availability === 'available';
        $driver->save();

        return redirect()->back()->with('success', 'Availability updated successfully.');
    }
     public function destroy($id)
    {
        $ride = Ride::findOrFail($id);

        // Optional: limit who can delete
        if ($ride->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed rides can be deleted.');
        }

        $ride->delete();

        return redirect()->back()->with('success', 'Ride deleted successfully!');
    }
}
    

