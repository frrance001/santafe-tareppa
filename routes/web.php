<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Driver\RideController as DriverRideController;
use App\Http\Controllers\Passenger\RideController as PassengerRideController;
use App\Http\Controllers\Passenger\DashboardController;
use App\Http\Controllers\Passenger\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;

// PUBLIC
Route::get('/', fn() => view('welcome'))->name('welcome');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// LOGOUT
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// OTP
Route::get('/otp-verify', [LoginController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp.verify.post');

// AUTHENTICATED ROUTES
Route::middleware(['auth'])->group(function () {

    // LOCATION
    Route::post('/update-location', [LocationController::class, 'update'])->name('update.location');

    // DRIVER ROUTES
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', fn() => view('driver.dashboard'))->name('dashboard');
        Route::get('/accept-rides', [DriverRideController::class, 'index'])->name('accept-rides');
        Route::post('/accept-rides/{ride}/accept', [DriverRideController::class, 'acceptRide'])->name('acceptRide');
        Route::post('/ride/{ride}/progress', [DriverRideController::class, 'markInProgress'])->name('ride.progress');
        Route::post('/ride/{ride}/complete', [DriverRideController::class, 'markComplete'])->name('ride.complete');
        Route::post('/pickup-passenger/{ride}/confirm', [DriverRideController::class, 'confirmPickup'])->name('pickup.confirm');
        Route::get('/rides', [DriverRideController::class, 'showAssignedRides'])->name('rides');
        Route::get('/set-availability', fn() => view('driver.set_availability', ['driver'=>auth()->user()]))->name('showAvailability');
        Route::post('/set-availability', [DriverRideController::class, 'setAvailability'])->name('setAvailability');
    });

    // PASSENGER ROUTES
    Route::prefix('passenger')->name('passenger.')->middleware('passenger')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/request', [PassengerRideController::class, 'create'])->name('request');
        Route::post('/request', [PassengerRideController::class, 'store'])->name('request.store');
        Route::get('/rides', [PassengerRideController::class, 'index'])->name('rides.index');
        Route::get('/waiting', [PassengerRideController::class, 'waiting'])->name('waiting');
        Route::get('/progress', [PassengerRideController::class, 'progress'])->name('progress');
        Route::get('/rate/{ride}', [PassengerRideController::class, 'rate'])->name('rate');
        Route::post('/rate/{ride}', [PassengerRideController::class, 'rateSubmit'])->name('rate.submit');
        Route::get('/drivers', [PassengerRideController::class, 'viewAvailableDrivers'])->name('view.drivers');
        Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
        Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
        Route::get('/report/{driver}', [ReportController::class, 'create'])->name('report');
        Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
        Route::patch('/rides/{id}/cancel', [PassengerRideController::class, 'cancel'])->name('rides.cancel');
        Route::post('/request-driver/{id}', [PassengerRideController::class, 'requestDriver'])->name('request.driver');
    });

    // ADMIN ROUTES
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/manage', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::resource('complaints', ComplaintController::class)->only(['index','show','update']);
        Route::get('/reported-drivers', [ReportController::class, 'index'])->name('reported.drivers');
        Route::get('/view-ride', [AdminDashboardController::class, 'viewCompletedRides'])->name('view-ride');
        Route::delete('/completed-rides/{ride}', [AdminDashboardController::class, 'destroy'])->name('completed-rides.destroy');
        Route::get('/activity-logging', [\App\Http\Controllers\Admin\ActivityLoggingController::class, 'index'])->name('activity.logging');
        Route::get('/download-database', [AdminDashboardController::class, 'downloadDatabase'])->name('download.db');
        Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
        Route::post('/verifications/{verification}/review', [VerificationController::class, 'review'])->name('verifications.review');
        Route::get('/reports', [AdminController::class, 'allReports'])->name('reports.index');
        Route::get('/ratings', [AdminController::class, 'allRatings'])->name('ratings.index');
    });

    // VERIFICATION ROUTES
    Route::get('/verification/create', [VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verification', [VerificationController::class, 'store'])->name('verification.store');
    Route::get('/verification/{verification}', [VerificationController::class, 'show'])->name('verification.show');
    Route::put('/verification/{verification}', [VerificationController::class, 'update'])->name('verification.update');
});
