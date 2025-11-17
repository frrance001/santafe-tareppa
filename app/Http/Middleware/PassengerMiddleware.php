<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PassengerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'Passenger') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

