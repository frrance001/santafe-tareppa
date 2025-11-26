<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show users grouped by role
    public function index()
    {
        $users = User::all()->groupBy('role');
        return view('admin.users.index', compact('users'));
    }

    // Approve user
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return redirect()->back()->with('success', 'User approved successfully!');
    }

    // Disapprove user with reason
   // Disapprove user with optional reason (no DB changes)
public function disapprove(Request $request, $id)
{
    $request->validate([
        'reason' => 'required|string|max:500',
    ]);

    $user = User::findOrFail($id);
    $user->status = 'disapproved';
    $user->save(); // only updates status

    // You can use the reason for logging, emailing, or flash message
    $reason = $request->reason;
    \Log::info("User {$user->fullname} disapproved. Reason: {$reason}");

    return redirect()->back()->with('success', "User disapproved successfully. Reason: {$reason}");
}

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    // -------------------------------
    // MANAGE USERS BY STATUS
    // -------------------------------

    public function managePending()
    {
        $status = 'pending';
        $users = User::where('status', $status)->get();

        return view('admin.manage.index', compact('users', 'status'));
    }

    public function manageApproved()
    {
        $status = 'approved';
        $users = User::where('status', $status)->get();

        return view('admin.manage.index', compact('users', 'status'));
    }

    public function manageDisapproved()
    {
        $status = 'disapproved';
        $users = User::where('status', $status)->get();

        return view('admin.manage.index', compact('users', 'status'));
    }
}
