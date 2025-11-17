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
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Passenger\DashboardController;
Route::get('/', function () {
    return view('welcome');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/otp-verify', [LoginController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp.verify.post');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
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

Route::middleware(['auth', 'passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/update-location', [DashboardController::class, 'updateLocation'])->name('update.location');
    Route::delete('/ride/{id}', [DashboardController::class, 'destroyRide'])->name('ride.destroy');
});

Route::get('/driver/dashboard', function () {
    return view('driver.dashboard');
})->name('driver.dashboard')->middleware('auth');

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
 
    Route::get('/request', [PassengerRideController::class, 'create'])->name('request');
    Route::post('/request', [PassengerRideController::class, 'store'])->name('request.store');
    Route::get('/rides', [PassengerRideController::class, 'index'])->name('rides.index');
});
//
Route::middleware('auth')->prefix('driver')->name('driver.')->group(function () {
    Route::get('/accept-rides', [DriverRideController::class, 'index'])->name('accept-rides');
    Route::post('/accept-rides/{id}', [DriverRideController::class, 'accept'])->name('accept-rides.accept');
});


Route::post('/driver/accept-rides/{id}/accept', [App\Http\Controllers\Driver\RideController::class, 'accept'])
    ->name('driver.accept-rides.accept')
    ->middleware('auth');
use App\Http\Controllers\ComplaintController;

Route::prefix('admin')->name('admin.')->middleware(['auth','is_admin'])->group(function () {
    Route::resource('complaints', \App\Http\Controllers\Admin\ComplaintController::class)
         ->only(['index','show','update']);
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

Route::middleware(['auth', 'passenger'])->group(function () {
    Route::get('/passenger/report/{driver}', [PassengerRideController::class, 'report'])
         ->name('passenger.report');
});

    // Request a driver (this is the route causing the issue)
    Route::post('/passenger/request-driver/{id}', [\App\Http\Controllers\Passenger\RideController::class, 'requestDriver'])->name('passenger.request.driver');
//
Route::get('/passenger/progress', [PassengerRideController::class, 'progress'])->name('passenger.progress');
Route::middleware(['auth', 'passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/report/{driver}', [ReportController::class, 'create'])->name('report');
    Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
});

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
Route::middleware(['auth'])->group(function () {
Route::get('/verification/create', [VerificationController::class, 'create'])->name('verification.create');
Route::post('/verification', [VerificationController::class, 'store'])->name('verification.store');
Route::get('/verification/{verification}', [VerificationController::class, 'show'])->name('verification.show');
Route::put('/verification/{verification}', [VerificationController::class, 'update'])->name('verification.update');
});


// Admin area (protect with your own middleware/policies)
Route::middleware(['auth','can:admin']) // replace with your gate/middleware
->group(function () {
Route::get('/admin/verifications', [VerificationController::class, 'index'])->name('verification.index');
Route::post('/admin/verifications/{verification}/review', [VerificationController::class, 'review'])->name('verification.review');
});
// Admin Reports & Ratings
Route::get('/admin/reports', [AdminController::class, 'allReports'])->name('admin.reports.index');
Route::get('/admin/ratings', [AdminController::class, 'allRatings'])->name('admin.ratings.index');

Route::middleware(['auth', 'role:passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/rides/waiting', [RideController::class, 'waiting'])->name('rides.waiting');
    Route::patch('/rides/{id}/cancel', [RideController::class, 'cancel'])->name('cancel');
});



// Admin user management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [AdminController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/disapprove', [AdminController::class, 'disapprove'])->name('users.disapprove');
   
});

// routes/web.php


Route::get('/passenger/payment', [PaymentController::class, 'index'])->name('passenger.payment.index');
Route::post('/passenger/payment/pay', [PaymentController::class, 'pay'])->name('passenger.payment.store');
Route::get('/passenger/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/passenger/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');


Route::delete('/passenger/ride/{id}', [RideController::class, 'destroy'])
    ->name('passenger.ride.destroy');


//location
Route::post('/update-location', [App\Http\Controllers\LocationController::class, 'update'])
    ->middleware('auth')
    ->name('update.location');

use App\Http\Controllers\Auth\OtpLoginController;

Route::post('/send-otp', [OtpLoginController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [OtpLoginController::class, 'verifyOtp'])->name('verify.otp');

use App\Http\Controllers\Admin\AdminDatabaseController;
Route::get('/admin/download-database', [AdminDatabaseController::class, 'download'])
    ->name('admin.download.db')
    ->middleware('admin'); // make sure only admin can access

Route::post('/login', [LoginController::class, 'login'])->name('login');

