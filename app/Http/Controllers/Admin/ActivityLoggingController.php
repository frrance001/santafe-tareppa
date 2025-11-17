<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        // Fetch only users with role 'Passenger'
        $users = User::where('role', 'Passenger')
                     ->orderBy('id', 'desc')
                     ->get();

        return view('admin.activity-logging', compact('users'));
    }
}

