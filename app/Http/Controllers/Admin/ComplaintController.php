<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\User;

class ComplaintController extends Controller
{
    /**
     * Show paginated complaints list with optional search and filters.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $ratings = Rating::with([
                'rater',
                'rateable.passenger',
                'rateable.driver'
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('comment', 'like', "%{$q}%")
                        ->orWhereHas('rater', fn($r) => 
                            $r->where('name', 'like', "%{$q}%")
                        )
                        ->orWhereHas('rateable.passenger', fn($p) => 
                            $p->where('name', 'like', "%{$q}%")
                        )
                        ->orWhereHas('rateable.driver', fn($d) =>
                            $d->where('name', 'like', "%{$q}%")
                        );
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', compact('ratings'));
    }


    /**
     * Show complaint details with full relations
     */
    public function show(Complaint $complaint)
    {
        $complaint->load([
            'passenger',
            'driver',
            'rating',
            'ride.passenger',
            'ride.driver'
        ]);

        return view('admin.complaints.show', compact('complaint'));
    }


    /**
     * Update complaint status
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
