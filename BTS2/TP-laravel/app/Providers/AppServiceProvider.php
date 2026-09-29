<?php

namespace App\Providers;

use App\Models\absence as AbsenceRecord;
use App\Models\users as UserRecord;
use App\Policies\AbsencePolicy;
use App\Policies\UsersPolicy;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\MotifRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentAbsenceRepository;
use App\Repositories\Eloquent\EloquentMotifRepository;
use App\Repositories\Eloquent\EloquentRoleRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AbsenceRepository::class, EloquentAbsenceRepository::class);
        $this->app->bind(MotifRepository::class, EloquentMotifRepository::class);
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(AbsenceRecord::class, AbsencePolicy::class);
        Gate::policy(UserRecord::class, UsersPolicy::class);
    }
}
