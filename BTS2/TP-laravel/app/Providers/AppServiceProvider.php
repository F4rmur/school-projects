<?php

namespace App\Providers;

use App\Models\absence;
use App\Models\users;
use App\Policies\AbsencePolicy;
use App\Policies\UsersPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(absence::class, AbsencePolicy::class);
        Gate::policy(users::class, UsersPolicy::class);
    }
}
