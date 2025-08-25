<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Passenger\RideController as PassengerRideController;
use App\Http\Controllers\Driver\RideController as DriverRideController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Driver\RideController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Passenger\PaymentController;


Route::get('/', function () {
    return view('welcome');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/manage', function () {
        return view('admin.manage');
    })->name('admin.manage');

    Route::get('/view-ride', function () {
        return view('admin.view-ride');
    })->name('admin.view-ride');

    Route::get('/complaints', function () {
        return view('admin.complaints');
    })->name('admin.complaints');

    Route::get('/payments', function () {
        return view('admin.payments');
    })->name('admin.payments');
});
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/manage', [UserController::class, 'index'])->name('admin.manage');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


Route::get('/driver/dashboard', function () {
    return view('driver.dashboard');
})->name('driver.dashboard')->middleware('auth');

Route::prefix('passenger')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('passenger.dashboard');
    })->name('passenger.dashboard');

    Route::get('/request', function () {
        return view('passenger.request');
    })->name('passenger.request');

    Route::get('/waiting', function () {
        return view('passenger.waiting');
    })->name('passenger.waiting');

    Route::get('/progress', function () {
        return view('passenger.progress');
    })->name('passenger.progress');

   
    Route::get('/rate', function () {
        return view('passenger.rate');

    })->name('passenger.rate');


});

Route::prefix('passenger')->middleware('auth')->group(function () {
    Route::get('/request', [RideController::class, 'create'])->name('passenger.request');
    Route::post('/request', [RideController::class, 'store'])->name('passenger.request.store');
});
// Driver routes
Route::middleware('auth')->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', fn() => view('driver.dashboard'))->name('dashboard');
    Route::get('/accept-rides', [DriverRideController::class, 'index'])->name('accept-rides');
});

// Passenger routes
Route::middleware('auth')->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/dashboard', fn() => view('passenger.dashboard'))->name('dashboard');
    Route::get('/request', [PassengerRideController::class, 'create'])->name('request');
    Route::post('/request', [PassengerRideController::class, 'store'])->name('request.store');
    Route::get('/rides', [PassengerRideController::class, 'index'])->name('rides.index');
});
//
Route::middleware('auth')->prefix('driver')->name('driver.')->group(function () {
    Route::get('/accept-rides', [DriverRideController::class, 'index'])->name('accept-rides');
    Route::post('/accept-rides/{id}', [DriverRideController::class, 'accept'])->name('accept-rides.accept');
});
Route::middleware('auth')->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/dashboard', [PassengerRideController::class, 'dashboard'])->name('dashboard');
    // other routes...
});

Route::post('/driver/accept-rides/{id}/accept', [App\Http\Controllers\Driver\RideController::class, 'accept'])
    ->name('driver.accept-rides.accept')
    ->middleware('auth');
use App\Http\Controllers\ComplaintController;

Route::middleware('auth')->group(function () {
    Route::get('/complaint', [ComplaintController::class, 'create'])->name('complaint.create');
    Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/driver/pickup-passenger', [DriverRideController::class, 'pickup'])->name('driver.pickup');
    Route::post('/driver/pickup-passenger/{ride}/confirm', [DriverRideController::class, 'confirmPickup'])->name('driver.pickup.confirm');
});
Route::get('/driver/rides', [DriverRideController::class, 'showAssignedRides'])->name('driver.rides');
Route::post('/driver/pickup/confirm/{ride}', [DriverRideController::class, 'confirmPickup'])->name('driver.pickup.confirm');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');
    Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/complaints/{id}', [\App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::patch('/complaints/{id}/resolve', [\App\Http\Controllers\Admin\ComplaintController::class, 'resolve'])->name('admin.complaints.resolve');
});

//
Route::get('/passenger/waiting', [PassengerRideController::class, 'waiting'])->name('passenger.waiting');
//
Route::post('/driver/set-availability', [App\Http\Controllers\Driver\RideController::class, 'setAvailability'])->name('driver.setAvailability');

// Show availability form
Route::get('/driver/set-availability', function () {
    return view('driver.set_availability', ['driver' => auth()->user()]);
})->middleware('auth')->name('driver.showAvailability');

// Handle availability form submission
Route::post('/driver/set-availability', [App\Http\Controllers\Driver\RideController::class, 'setAvailability'])->name('driver.setAvailability');
//

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');   // ← This defines admin.users.index
});
//
Route::middleware('auth')->group(function () {
    Route::get('/passenger/drivers', [\App\Http\Controllers\Passenger\RideController::class, 'viewAvailableDrivers'])->name('passenger.view.drivers');
    Route::get('/passenger/request', [\App\Http\Controllers\Passenger\RideController::class, 'create'])->name('passenger.request');
    Route::post('/passenger/request', [\App\Http\Controllers\Passenger\RideController::class, 'store'])->name('passenger.request.store');
    Route::get('/passenger/dashboard', [\App\Http\Controllers\Passenger\RideController::class, 'dashboard'])->name('passenger.dashboard');
    Route::get('/passenger/waiting', [\App\Http\Controllers\Passenger\RideController::class, 'waiting'])->name('passenger.waiting');
    Route::get('/passenger/rides', [\App\Http\Controllers\Passenger\RideController::class, 'index'])->name('passenger.rides');
});


    // Request a driver (this is the route causing the issue)
    Route::post('/passenger/request-driver/{id}', [\App\Http\Controllers\Passenger\RideController::class, 'requestDriver'])->name('passenger.request.driver');
//
Route::get('/passenger/progress', [PassengerRideController::class, 'progress'])->name('passenger.progress');
//

Route::get('/passenger/report/{driver}', [PassengerRideController::class, 'report'])->name('passenger.report');
//
Route::middleware('auth')->group(function () {
    Route::get('/passenger/rate/{ride}', [\App\Http\Controllers\Passenger\RideController::class, 'rate'])->name('passenger.rate');
    Route::post('/passenger/rate/{ride}', [\App\Http\Controllers\Passenger\RideController::class, 'rateSubmit'])->name('passenger.rate.submit');
});
//
Route::middleware(['auth'])->prefix('driver')->name('driver.')->group(function () {
    Route::post('/ride/{ride}/progress', [DriverRideController::class, 'markInProgress'])->name('ride.progress');
    Route::post('/ride/{ride}/complete', [DriverRideController::class, 'markComplete'])->name('ride.complete');
});
//
Route::get('/completed-rides', [RideController::class, 'completedRides'])->name('driver.completed.rides');

Route::middleware(['auth', 'passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/report/{driver}', [ReportController::class, 'create'])->name('report');
    Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
});


Route::post('/report/{driverId}', [\App\Http\Controllers\Passenger\RideController::class, 'submitReport'])->name('report.submit');
//
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reported-drivers', [\App\Http\Controllers\ReportController::class, 'index'])->name('reported.drivers');
});
Route::get('/admin/view-ride', [AdminDashboardController::class, 'viewCompletedRides'])->name('admin.view-ride');
Route::delete('/admin/completed-rides/{ride}', [AdminDashboardController::class, 'destroy'])
    ->name('admin.completed-rides.destroy');

    Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
Route::get('/request-ride/{driver?}', [PassengerRideController::class, 'create'])->name('passenger.request');



Route::post('/driver/accept-rides/{ride}/accept', [DriverRideController::class, 'acceptRide'])
    ->name('driver.acceptRide');
 Route::prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
});