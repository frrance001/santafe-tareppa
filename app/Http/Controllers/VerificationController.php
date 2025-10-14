<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    // Show create form
    public function create()
    {
        $verification = Verification::where('user_id', Auth::id())->latest()->first();
        return view('verification.create', compact('verification'));
    }

    // Store new submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_permit'    => ['required','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'police_clearance'   => ['required','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'barangay_clearance' => ['required','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $paths = [
            'business_permit_path'    => $request->file('business_permit')->store('permits/business', 'public'),
            'police_clearance_path'   => $request->file('police_clearance')->store('permits/police', 'public'),
            'barangay_clearance_path' => $request->file('barangay_clearance')->store('permits/barangay', 'public'),
        ];

        $verification = Verification::create([
            'user_id' => Auth::id(),
            ...$paths,
            'status' => 'pending',
        ]);

        return redirect()->route('verification.show', $verification)
            ->with('success', 'Submitted! We\'ll review shortly.');
    }

    // Show submission (for user)
    public function show(Verification $verification)
    {
        $this->authorize('view', $verification); // optional if using policies
        return view('verification.show', compact('verification'));
    }

    // Allow user to update/resubmit (replace photos)
    public function update(Request $request, Verification $verification)
    {
        $this->authorize('update', $verification);

        $validated = $request->validate([
            'business_permit'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'police_clearance'   => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'barangay_clearance' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $data = [];

        if ($request->hasFile('business_permit')) {
            if ($verification->business_permit_path) {
                Storage::disk('public')->delete($verification->business_permit_path);
            }
            $data['business_permit_path'] = $request->file('business_permit')->store('permits/business', 'public');
        }

        if ($request->hasFile('police_clearance')) {
            if ($verification->police_clearance_path) {
                Storage::disk('public')->delete($verification->police_clearance_path);
            }
            $data['police_clearance_path'] = $request->file('police_clearance')->store('permits/police', 'public');
        }

        if ($request->hasFile('barangay_clearance')) {
            if ($verification->barangay_clearance_path) {
                Storage::disk('public')->delete($verification->barangay_clearance_path);
            }
            $data['barangay_clearance_path'] = $request->file('barangay_clearance')->store('permits/barangay', 'public');
        }

        if (!empty($data)) {
            $verification->update($data + ['status' => 'pending']);
        }

        return back()->with('success', 'Updated!');
    }
}
