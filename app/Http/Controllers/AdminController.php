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

   // Disapprove user
public function disapprove(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Mark user as disapproved
    $user->status = 'disapproved';
    $user->save();

    // Store the reason temporarily in session
    $request->session()->flash('disapproval_reason_'.$user->id, $request->reason);

    return redirect()->back()->with('success', 'Driver disapproved successfully!');
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

    // PENDING USERS
   public function managePending()
{
    $status = 'pending';
    $users = User::where('status', $status)
                 ->where('role', 'Driver')
                 ->get();

    return view('admin.manage.index', compact('users', 'status'));
}


    // APPROVED USERS
    public function manageApproved()
    {
        $status = 'approved';
        $users = User::where('status', $status)->get();

        return view('admin.manage.index', compact('users', 'status'));
    }

    // DISAPPROVED USERS
    public function manageDisapproved()
    {
        $status = 'disapproved';
        $users = User::where('status', $status)->get();

        return view('admin.manage.index', compact('users', 'status'));
    }
}
