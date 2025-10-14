<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
                    ->orderBy('role')
                    ->get()
                    ->groupBy('role');

        return view('admin.manage', compact('users'));
    }

    public function create()
    {
        return view('admin.create-user');
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
            'city' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            // ✅ Now required
            'business_permit'    => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'barangay_clearance' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'police_clearance'   => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
        ]);

        // Upload files
        $photoPath = $request->hasFile('photo') 
            ? $request->file('photo')->store('users', 'public') 
            : null;

        $businessPermitPath = $request->file('business_permit')->store('documents/business_permit', 'public');
        $barangayClearancePath = $request->file('barangay_clearance')->store('documents/barangay_clearance', 'public');
        $policeClearancePath = $request->file('police_clearance')->store('documents/police_clearance', 'public');

        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'sex' => $request->sex,
            'city' => $request->city,
            'photo' => $photoPath,
            'business_permit' => $businessPermitPath,
            'barangay_clearance' => $barangayClearancePath,
            'police_clearance' => $policeClearancePath,
        ]);

        return redirect()->route('admin.manage')->with('success', 'User created successfully.');
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
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:Driver,Passenger',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:1|max:100',
            'sex' => 'required|in:male,female,other',
            'city' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            // ✅ Required on update too
            'business_permit'    => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'barangay_clearance' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'police_clearance'   => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
        ]);

        $user = User::findOrFail($id);

        // Replace files
        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $user->photo = $request->file('photo')->store('users', 'public');
        }

        if ($request->hasFile('business_permit')) {
            if ($user->business_permit) Storage::disk('public')->delete($user->business_permit);
            $user->business_permit = $request->file('business_permit')->store('documents/business_permit', 'public');
        }

        if ($request->hasFile('barangay_clearance')) {
            if ($user->barangay_clearance) Storage::disk('public')->delete($user->barangay_clearance);
            $user->barangay_clearance = $request->file('barangay_clearance')->store('documents/barangay_clearance', 'public');
        }

        if ($request->hasFile('police_clearance')) {
            if ($user->police_clearance) Storage::disk('public')->delete($user->police_clearance);
            $user->police_clearance = $request->file('police_clearance')->store('documents/police_clearance', 'public');
        }

        $user->update($request->only('fullname','email','role','phone','age','sex','city'));

        return redirect()->route('admin.manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo) Storage::disk('public')->delete($user->photo);
        if ($user->business_permit) Storage::disk('public')->delete($user->business_permit);
        if ($user->barangay_clearance) Storage::disk('public')->delete($user->barangay_clearance);
        if ($user->police_clearance) Storage::disk('public')->delete($user->police_clearance);

        $user->delete();

        return redirect()->route('admin.manage')->with('success', 'User deleted successfully.');
    }
    public function approve($id)
{
    $user = User::findOrFail($id);
    $user->status = 'approved'; // ✅ Assuming you have a status column
    $user->save();

    // Notify the user
    $user->notify(new UserApprovedNotification());

    return redirect()->back()->with('success', 'User approved successfully!');
}

}
