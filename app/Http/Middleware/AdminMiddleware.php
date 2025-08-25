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
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints');
});

    

