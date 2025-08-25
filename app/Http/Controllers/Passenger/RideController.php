<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    // Show available roles (for debugging or dashboard)
    public function roles()
    {
        // All passengers
        $passengers = User::where('role', 'passenger')->get();

        // All drivers
        $drivers = User::where('role', 'driver')->get();

        // Current user role
        $currentUserRole = Auth::check() ? Auth::user()->role : null;

        return view('passenger.roles', compact('passengers', 'drivers', 'currentUserRole'));
    }

    // Passenger selects a driver (only drivers can be selected)
    public function create($driverId = null)
    {
        $selectedDriver = null;

        if ($driverId) {
            $selectedDriver = User::where('id', $driverId)
                                  ->where('role', 'driver')
                                  ->first();
        }

        return view('passenger.request', compact('selectedDriver'));
    }

    // Passenger creates ride request
    public function store(Request $request)
    {
        $request->validate([
            'pickup_location'   => 'required|string|max:255',
            'dropoff_location'  => 'required|string|max:255',
            'phone_number'      => 'required|regex:/^\d{11}$/',
            'driver_id'         => 'nullable|exists:users,id',
        ]);

        Ride::create([
            'user_id'          => auth()->id(),
            'pickup_location'  => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'phone_number'     => $request->phone_number,
            'driver_id'        => $request->driver_id,
            'status'           => 'waiting',
        ]);

        return redirect()->route('passenger.waiting')->with('success', 'Ride requested successfully!');
    }

    public function index()
    {
        $rides = Ride::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('passenger.rides.index', compact('rides'));
    }

    public function dashboard()
    {
        $rides = Ride::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('passenger.dashboard', compact('rides'));
    }

    public function waiting()
    {
        $rides = Ride::where('user_id', Auth::id())
                    ->where('status', 'waiting')
                    ->latest()
                    ->get();

        return view('passenger.waiting', compact('rides'));
    }

    // Passenger can only see available drivers
    public function viewAvailableDrivers()
    {
        $drivers = User::where('role', 'driver')
                    ->where('is_available', true)
                    ->orderBy('fullname')
                    ->get();

        return view('passenger.view-available-drivers', compact('drivers'));
    }

    // Passenger requests a ride from a driver
    public function requestDriver(Request $request, $id)
    {
        $request->validate([
            'pickup_location'   => 'required|string|max:255',
            'dropoff_location'  => 'required|string|max:255',
            'phone_number'      => 'nullable|regex:/^\d{11}$/',
        ]);

        $driver = User::where('id', $id)
                    ->where('role', 'driver')
                    ->firstOrFail();

        Ride::create([
            'user_id'          => auth()->id(),
            'pickup_location'  => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'phone_number'     => $request->phone_number,
            'driver_id'        => $driver->id,
            'status'           => 'waiting',
        ]);

        return redirect()->route('passenger.dashboard')->with('success', 'Ride request sent successfully!');
    }

    public function progress()
    {
        $ride = Ride::with('driver')
                    ->where('user_id', auth()->id())
                    ->whereIn('status', ['waiting', 'accepted', 'in_progress','completed'])
                    ->latest()
                    ->first();

        return view('passenger.progress', compact('ride'));
    }

    public function rate($rideId)
    {
        $ride = Ride::with('driver')->where('id', $rideId)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        return view('passenger.rate', compact('ride'));
    }

    public function rateSubmit(Request $request, $rideId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $ride = Ride::where('id', $rideId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $ride->rating = $request->rating;
        $ride->feedback = $request->feedback;
        $ride->save();

        return redirect()->route('passenger.dashboard')->with('success', 'Thank you for rating your driver!');
    }

    public function report($driverId)
    {
        $driver = User::where('id', $driverId)
                      ->where('role', 'driver')
                      ->firstOrFail();

        return view('passenger.report-driver', compact('driver'));
    }

    public function submitReport(Request $request, $driverId)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $driver = User::where('id', $driverId)
                      ->where('role', 'driver')
                      ->firstOrFail();

        Report::create([
            'passenger_id' => auth()->id(),
            'driver_id'    => $driver->id,
            'reason'       => $request->reason,
        ]);

        return redirect()->route('passenger.dashboard')->with('success', 'Report submitted successfully.');
    }
}
