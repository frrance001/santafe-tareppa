<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function setAvailability(Request $request)
{
    $request->validate([
        'availability' => 'required|in:available,not_available',
    ]);

    $driver = auth()->user();
    $driver->availability = $request->availability;
    $driver->save();

    return redirect()->back()->with('success', 'Your availability has been updated.');
}
//
}
