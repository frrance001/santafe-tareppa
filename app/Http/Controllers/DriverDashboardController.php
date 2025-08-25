<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ride;

class DriverDashboardController extends Controller
{
    public function index()
{
    $driverId = Auth::id();

    $completedRides = Ride::where('driver_id', $driverId)
        ->where('status', 'completed')
        ->count();

    $currentRides = Ride::where('driver_id', $driverId)
        ->where('status', 'ongoing')
        ->count();

    $fixedFare = 50;
    $earnings = $completedRides * $fixedFare;

    $feedbacks = Ride::where('driver_id', $driverId)
        ->whereNotNull('feedback') // assuming feedback is a column in rides
        ->with('passenger')        // assumes Ride has passenger relationship
        ->latest()
        ->get();

    return view('driver.dashboard', compact(
        'completedRides',
        'currentRides',
        'earnings',
        'feedbacks'
    ));
}

}
