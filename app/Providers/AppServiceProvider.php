<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        RateLimiter::for('global', function (Request $request) {
            $ip = $request->ip();

            // Check if the IP is banned
            if (Cache::has('banned:' . $ip)) {
                // Return none if banned
                return Limit::none();  
            }

            // Define the rate limit
            $limit = Limit::perMinute(10)->by($ip);

            // Check if the rate limit has been exceeded
            if (RateLimiter::tooManyAttempts($limit->key, $limit->maxAttempts)) {
                // Ban the IP for 1 hour
                Cache::put('banned:' . $ip, true, now()->addHour());
            }

            return $limit; // Ensure to return the limit object
        });
    }
}
