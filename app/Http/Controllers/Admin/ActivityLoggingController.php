<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        // Fetch activity logs from the database if needed
        // $logs = ActivityLog::latest()->get();

        return view('admin.activity-logging'); // create this Blade view
    }
}
