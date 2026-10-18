<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        // Asset/storage URLs must always use the public domain, even when the
        // request comes from the Next.js server over loopback.
        URL::forceRootUrl(config('app.url'));
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('api', function (Request $request) {
          
            if ($request->isMethod('OPTIONS')) {
                return Limit::none();
            }

            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        }); 

        User::observe(UserObserver::class);
    }
}