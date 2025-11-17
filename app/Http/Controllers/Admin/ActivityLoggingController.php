<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        // Get only the currently logged-in user
        $user = Auth::user();

        // Wrap in a collection so the Blade foreach works
        $users = collect([$user]);

        return view('admin.activity-logging', compact('users'));
    }
}
