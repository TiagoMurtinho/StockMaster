<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        RateLimiter::for('global', function (Request $request) {

            if (!$request->hasCookie('device_id')) {
                return Limit::none()->response(function() {
                    return response()->json([
                        'message' => 'Access denied: Please visit / to receive a device_id'
                    ], 429);
                });
            }

            $deviceId = $request->cookie('device_id');

            Log::info('Device ID: ' . $deviceId);

            $key = 'global:' . $deviceId;

            $currentAttempts = RateLimiter::attempts($key);
            Log::info('Attempts: ' . $currentAttempts);

            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'message' => 'Too many requests.'
                ], 429);
            }

            RateLimiter::hit($key);

            Log::info('Hit registered for Device ID: ' . $deviceId);
            return Limit::perSecond(5)->by($deviceId);
        });

        RateLimiter::for('home', function (Request $request) {
            if (!$request->hasCookie('device_id')) {
                return Limit::perSecond(20)->by($request->ip());
            } else {
                $deviceId = $request->cookie('device_id');
                return Limit::perSecond(5)->by($deviceId);
            }
        });
    }
}
