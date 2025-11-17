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

        // Only allow if role is Passenger
        if ($user->role !== 'Passenger') {
            abort(403, 'Unauthorized');
        }

        return view('admin.activity-logging', compact('user'));
    }
}


