<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
{
    $users = \App\Models\User::where('role', '!=', 'admin')
                ->orderBy('role')
                ->get()
                ->groupBy('role');

    return view('admin.manage', compact('users'));
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('fullname', 'email', 'role'));

        return redirect()->route('admin.manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.manage')->with('success', 'User deleted successfully.');
    }
    public function store(Request $request)
{
   $request->validate([
    'fullname' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'role' => 'required|in:Driver,Passenger',
    'password' => 'required|string|min:6',
    'phone' => 'required|string|max:20',
    'age' => 'required|integer|min:1|max:100',
     'sex' => 'required|in:male,female,other',
]);

User::create([
    'fullname' => $request->fullname,
    'email' => $request->email,
    'role' => $request->role,
    'phone' => $request->phone,
    'password' => Hash::make($request->password),
    'age' => $request->age,
    'sex' => $request->sex,
    
]);

    return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
}
public function create()
{
    return view('admin.create-user');
}

}


