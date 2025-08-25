<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Payment;



class AdminDashboardController extends Controller
{
    public function index()
    {    $totalRequests = Ride::count();
    $totalAcceptedRequests = Ride::where('status', 'accepted')->count();
    $totalInProgress = Ride::where('status', 'in_progress')->count();
    $totalCompleted = Ride::where('status', 'completed')->count();
    $totalPassengers = User::where('role', 'Passenger')->count();
    $totalDrivers = User::where('role', 'Driver')->count();

    return view('admin.dashboard', compact(
        'totalRequests',
        'totalAcceptedRequests',
        'totalInProgress',
        'totalCompleted',
        'totalPassengers',
        'totalDrivers'
    ));
}

    public function viewCompletedRides()
{
    $rides = Ride::with(['passenger', 'driver'])
        ->where('status', 'completed')
        ->latest()
        ->get();

    return view('admin.view-ride', compact('rides'));
}public function passenger()
{
    return $this->belongsTo(User::class, 'passenger_id');
}

public function driver()
{
    return $this->belongsTo(User::class, 'driver_id');
}
 public function destroy(Ride $ride)
    {
        $ride->delete();

        return redirect()->back()->with('success', 'Ride deleted successfully.');
    }
    public function payments()
{
    $payments = Payment::with('user')->latest()->get();
    return view('admin.payments', compact('payments'));
}
}

