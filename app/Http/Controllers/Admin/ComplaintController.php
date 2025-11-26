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
    $passengerId = $request->query('passenger_id');
    $driverId    = $request->query('driver_id');
    $score       = $request->query('score');

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
        ->when($passengerId, function ($query, $passengerId) {
            $query->whereHas('rateable.passenger', fn($q) => $q->where('id', $passengerId));
        })
        ->when($driverId, function ($query, $driverId) {
            $query->whereHas('rateable.driver', fn($q) => $q->where('id', $driverId));
        })
        ->when($score, function ($query, $score) {
            $query->where('score', $score);
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

    // Fetch passengers and drivers for the filter dropdowns
    $passengers = User::where('role', 'passenger')->get();
    $drivers    = User::where('role', 'driver')->get();

    return view('admin.complaints.index', compact('ratings', 'passengers', 'drivers'));
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
