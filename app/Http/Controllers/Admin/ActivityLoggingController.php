<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        // Get all users
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.activity-logging', compact('users'));
    }
}
