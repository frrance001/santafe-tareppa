<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot()
{
    \Illuminate\Support\Facades\Response::macro('noVersion', function ($response) {
        $response->headers->remove('X-Powered-By');
        return $response;
    });
}

}
