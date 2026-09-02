<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\NavigationRepositoryInterface::class,
            \App\Repositories\Eloquent\NavigationRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ArticleRepositoryInterface::class,
            \App\Repositories\Eloquent\ArticleRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\EventRepositoryInterface::class,
            \App\Repositories\Eloquent\EventRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\CabinetMemberRepositoryInterface::class,
            \App\Repositories\Eloquent\CabinetMemberRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\ProjectRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PartnerRepositoryInterface::class,
            \App\Repositories\Eloquent\PartnerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\AspirationRepositoryInterface::class,
            \App\Repositories\Eloquent\AspirationRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\RoleRepositoryInterface::class,
            \App\Repositories\Eloquent\RoleRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\VotingSessionRepositoryInterface::class,
            \App\Repositories\Eloquent\VotingSessionRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\CandidateRepositoryInterface::class,
            \App\Repositories\Eloquent\CandidateRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        // Once the site is served over TLS, generate every URL (assets, form
        // actions, signed verification links) as https so the browser does not
        // block them as mixed content.
        if ($this->app->isProduction() || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('aspirations', function (Request $request) {
            if ($request->user()) {
                return Limit::perDay(10)->by($request->user()->id);
            }
            // Anonymous users: 3 per day by IP
            return Limit::perDay(3)->by($request->ip());
        });

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
