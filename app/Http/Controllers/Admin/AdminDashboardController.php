<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

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

        // Send approval email
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

        // Send disapproval email
        Mail::raw(
            "Hello {$user->fullname},\n\nWe regret to inform you that your driver account has been disapproved by the admin. For more details, please contact support.\n\nThank you,\nSantafe Tareppa Team",
            function($message) use ($user) {
                $message->to($user->email)
                        ->subject("Driver Account Disapproved - Santafe Tareppa");
            }
        );

        return back()->with('success', 'User disapproved and email sent.');
    }
    public function downloadDatabase()
{
    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $host = env('DB_HOST', '127.0.0.1');

    try {
        $pdo = new \PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        return response()->streamDownload(function() use ($pdo, $tables) {
            foreach ($tables as $table) {
                // Drop table
                echo "DROP TABLE IF EXISTS `$table`;\n";

                // Create table
                $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(\PDO::FETCH_ASSOC);
                echo $create['Create Table'] . ";\n\n";

                // Insert data in chunks to avoid memory issues
                $stmt = $pdo->query("SELECT * FROM `$table`");
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $columns = implode('`,`', array_keys($row));
                    $values = implode(',', array_map([$pdo, 'quote'], array_values($row)));
                    echo "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
                }
                echo "\n\n";
            }
        }, 'system.sql', [
            'Content-Type' => 'application/sql',
        ]);

    } catch (\Exception $e) {
        return back()->with('error', 'Database export failed: ' . $e->getMessage());
    }
}
}
