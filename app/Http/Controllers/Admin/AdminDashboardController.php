<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminDashboardController extends Controller
{
    // ==========================
    // MAIN DASHBOARD
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
    // COMPLETED RIDES
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
    // PAYMENTS
    // ==========================
    public function payments()
    {
        $payments = Payment::with('user')->latest()->get();
        return view('admin.payments', compact('payments'));
    }

    // ==========================
    // USER MANAGEMENT (ALL STATUSES)
    // ==========================
    public function managePending()
    {
        $status = 'pending';
        $users = User::where('status', $status)
                     ->where('role', 'Driver')
                     ->get();

        return view('admin.manage.index', compact('users', 'status'));
    }

    public function manageApproved()
    {
        $status = 'approved';
        $users = User::where('status', $status)->get();
        return view('admin.manage.index', compact('users', 'status'));
    }

    public function manageDisapproved()
    {
        $status = 'disapproved';
        $users = User::where('status', $status)->get();
        return view('admin.manage.index', compact('users', 'status'));
    }

    // ==========================
    // APPROVE USER + EMAIL
    // ==========================
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        // Send email
        Mail::raw(
            "Hello {$user->fullname},\n\nYour driver account has been approved. You may now log in and start accepting rides.\n\nThank you,\nSantafe Tareppa Team",
            function($message) use ($user) {
                $message->to($user->email)
                        ->subject("Driver Account Approved - Santafe Tareppa");
            }
        );

        return back()->with('success', 'User approved and email sent.');
    }

    // ==========================
    // DISAPPROVE USER + EMAIL
    // ==========================
    public function disapprove(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $reason = $request->reason ?? "No reason provided.";

        $user->status = 'disapproved';
        $user->save();

        // Email
        Mail::raw(
            "Hello {$user->fullname},\n\nYour driver account has been disapproved.\nReason: $reason\n\nThank you,\nSantafe Tareppa Team",
            function($message) use ($user) {
                $message->to($user->email)
                        ->subject("Driver Account Disapproved - Santafe Tareppa");
            }
        );

        return back()->with('success', 'User disapproved and email sent.');
    }

    // ==========================
    // DELETE USER
    // ==========================
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    // ==========================
    // DELETE RIDE
    // ==========================
    public function deleteRide(Ride $ride)
    {
        $ride->delete();
        return back()->with('success', 'Ride deleted successfully.');
    }

    // ==========================
    // DOWNLOAD DATABASE
    // ==========================
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

                    echo "DROP TABLE IF EXISTS `$table`;\n";

                    $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(\PDO::FETCH_ASSOC);
                    echo $create['Create Table'] . ";\n\n";

                    $stmt = $pdo->query("SELECT * FROM `$table`");

                    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        $columns = implode('`,`', array_keys($row));
                        $values = implode(',', array_map([$pdo, 'quote'], array_values($row)));
                        echo "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
                    }
                    echo "\n\n";
                }
            }, 'system.sql', ['Content-Type' => 'application/sql']);

        } catch (\Exception $e) {
            return back()->with('error', 'Database export failed: ' . $e->getMessage());
        }
    }
}
