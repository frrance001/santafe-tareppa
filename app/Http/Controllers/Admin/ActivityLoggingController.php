<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivityLoggingController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // get the logged-in user
        return view('admin.activity-logging', compact('user'));
    }
}
