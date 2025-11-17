<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Ensure the logged-in user is a Passenger
        if ($user && $user->role === 'Passenger') {
            return view('admin.activity-logging', ['user' => $user]);
        }

        abort(403, 'Unauthorized');
    }
}
