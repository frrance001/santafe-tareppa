<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\Route;


class AdminDashboardController extends Controller
{
    // ==========================
    // Dashboard
    // ==========================
    public function index()
    {    
        $totalRequests = Ride::count();
        $totalAcceptedRequests = Ride::where('status', 'accepted')->count();
        $totalInProgress = Ride::where('status', 'in_progress')->count();
        $totalCompleted = Ride::where('status', 'completed')->count();
        $totalPassengers = User::where('role', 'Passenger')->count();
        $totalDrivers = User::where('role', 'Driver')->count();

        return view('admin.dashboard', compact(
            'totalRequests',
            'totalAcceptedRequests',
            'totalInProgress',
            'totalCompleted',
            'totalPassengers',
            'totalDrivers'
        ));
    }

    // ==========================
    // Completed Rides
    // ==========================
    public function viewCompletedRides()
    {
        $rides = Ride::with(['passenger', 'driver'])
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('admin.view-ride', compact('rides'));
    }

    // ==========================
    // Ride Relationships
    // ==========================
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // ==========================
    // Delete Ride
    // ==========================
    public function destroy(Ride $ride)
    {
        $ride->delete();
        return redirect()->back()->with('success', 'Ride deleted successfully.');
    }

    // ==========================
    // Payments
    // ==========================
    public function payments()
    {
        $payments = Payment::with('user')->latest()->get();
        return view('admin.payments', compact('payments'));
    }

    // ==========================
    // Approve User + Send Email
    // ==========================
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        Mail::raw(
            "Hello {$user->fullname},\n\nYour driver account has been approved by the admin. You can now log in to your account and start accepting rides.\n\nThank you,\nSantafe Tareppa Team",
            function($message) use ($user) {
                $message->to($user->email)
                        ->subject("Driver Account Approved - Santafe Tareppa");
            }
        );

        return back()->with('success', 'User approved and email sent.');
    }

    // ==========================
    // Disapprove User + Send Email
    // ==========================
    public function disapprove($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'disapproved';
        $user->save();

        Mail::raw(
            "Hello {$user->fullname},\n\nWe regret to inform you that your driver account has been disapproved by the admin. For more details, please contact support.\n\nThank you,\nSantafe Tareppa Team",
            function($message) use ($user) {
                $message->to($user->email)
                        ->subject("Driver Account Disapproved - Santafe Tareppa");
            }
        );

        return back()->with('success', 'User disapproved and email sent.');
    }

    // ==========================
    // Download Database
    // ==========================
    public function downloadDatabase()
    {
        $fileName = 'backup_' . date('Y_m_d_His') . '.sql';

        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > " . storage_path($fileName);

        system($command, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Database backup failed.');
        }

        return response()->download(storage_path($fileName))->deleteFileAfterSend(true);
    }
}
