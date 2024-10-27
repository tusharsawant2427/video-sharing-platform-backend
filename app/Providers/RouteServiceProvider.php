<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api', 'throttle:api')
                ->prefix('api/v1/users')
                ->group(base_path('app/Features/Users/Http/v1/routes.php'));

            Route::middleware( 'api', 'auth:api','throttle:api')
                ->prefix('api/v1/posts')
                ->group(base_path('app/Features/Posts/Http/v1/routes.php'));
        });


    }
}
