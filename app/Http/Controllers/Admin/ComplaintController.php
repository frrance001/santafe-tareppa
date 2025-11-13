<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\Rating;

class ComplaintController extends Controller
{
    /**
     * Show paginated complaints list (with optional search).
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $ratings = Rating::with('rater') // eager load rater
            ->when($q, function($query) use ($q) {
                $query->where('comment', 'like', "%{$q}%")
                      ->orWhereHas('rater', fn($r) => $r->where('name', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', compact('ratings'));
    }

    /**
     * Show complaint details with related ratings.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load([
            'passenger',
            'driver',
            'rating', // single rating linked to complaint
        ]);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Update complaint status (resolve, close, etc.).
     */
    public function update(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved,closed',
        ]);

        $complaint->update($data);

        return back()->with('success', 'Complaint status updated.');
    }
    
}
