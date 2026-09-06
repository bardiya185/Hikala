<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      
        RateLimiter::for('api', function (Request $request) {
          
            if ($request->isMethod('OPTIONS')) {
                return Limit::none();
            }

            return Limit::perMinute(600)->by(
                $request->user()?->id ?: $request->ip()
            );
        }); 

        User::observe(UserObserver::class);
    }
}