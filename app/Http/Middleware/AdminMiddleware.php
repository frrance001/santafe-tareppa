<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'Admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
// Replace this temporarily


    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/complaints', [AdminController::class, 'complaints'])->name('admin.complaints.index');
    Route::get('/complaints/{id}', [AdminController::class, 'showComplaint'])->name('admin.complaints.show');
    Route::patch('/complaints/resolve/{id}', [AdminController::class, 'resolveComplaint'])->name('admin.complaints.resolve');
});


