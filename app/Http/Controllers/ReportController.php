<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
   public function create($driverId)
{
    $driver = \App\Models\User::findOrFail($driverId);
    return view('passenger.report', compact('driver'));
}

public function submit(Request $request)
{
    $request->validate([
        'driver_id' => 'required|exists:users,id',
        'description' => 'required|string|min:10',
    ]);

    $passengerId = auth()->id();
    if (!$passengerId) {
        return back()->with('error', 'Not logged in.');
    }

    $report = new \App\Models\Report();
    $report->passenger_id = $passengerId;
    $report->driver_id = $request->driver_id;
    $report->description = $request->description;
    $report->save();

    return redirect()->route('passenger.dashboard')->with('success', 'Report submitted successfully.');
}


}


