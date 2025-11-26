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
    public function disapprove(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($id);
        $user->status = 'disapproved';
        $user->disapproval_reason = $request->reason; // make sure this column exists in DB
        $user->save();

        return redirect()->back()->with('success', 'User disapproved successfully.');
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
