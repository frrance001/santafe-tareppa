<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Show paginated complaints list (with optional search).
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $complaints = Complaint::with(['passenger', 'driver', 'rating'])
            ->when($q, function ($qb) use ($q) {
                $qb->where('message', 'like', "%{$q}%")
                    ->orWhereHas('passenger', fn($p) => $p->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('driver', fn($d) => $d->where('name', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
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
