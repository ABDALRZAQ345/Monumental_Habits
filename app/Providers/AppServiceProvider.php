<?php

namespace App\Providers;

use App\Models\Habit;
use App\Observers\HabitObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->observers();
        $this->rateLimiters();

        $this->routes();
        $this->productionConfigurations();
        $this->PassWordConfigurations();
    }

    private function observers(): void {
        Habit::observe(HabitObserver::class);
    }

    private function rateLimiters(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('send_confirmation_code', function (Request $request) {
            return [
                Limit::perMinutes(30, 30)->by($request->ip()),
                Limit::perDay(200)->by($request->ip()),
            ];
        });
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(30, 2)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('change_password', function (Request $request) {
            return Limit::perDay(5)->by($request->user()?->id);
        });
    }

    private function routes(): void
    {
        $apiRouteFiles = [
            'auth.php',
            'user.php',
            'googleAuth.php',
            'habits.php',
        ];
        foreach ($apiRouteFiles as $routeFile) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path("routes/Api/{$routeFile}"));
        }
    }

    private function productionConfigurations(): void
    {
        Model::shouldBeStrict(! app()->environment('production'));
        Model::preventLazyLoading(! app()->environment('production'));

    }

    private function PassWordConfigurations(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }
}
