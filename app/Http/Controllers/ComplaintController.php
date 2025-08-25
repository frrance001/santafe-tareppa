<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required|min:10',
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('status', 'Complaint submitted successfully.');
    }

    public function index()
    {
        $complaints = Complaint::latest()->paginate(10);
        return view('admin.complaints.index', compact('complaints'));
    }
}
