<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->is_super_admin) {
                return true;
            }
        });

        Gate::define('show-tasks', function ($user, $committee) {
            if($user->committees->contains($committee))
            return true;
        });

        Gate::define('manage-tasks', function ($user, $committee) {
            $admin = auth()->guard('admin')->user();

            if ($admin && $admin->committees->contains($committee)) {
                return true;
            }
            return false;
        });

        Gate::define('show-sessions', function ($user, $committee) {
            if ($user->committees->contains($committee))
                return true;
        });

        Gate::define('manage-sessions', function ($user, $committee) {
            $admin = auth()->guard('admin')->user();

            if ($admin && $admin->committees->contains($committee)) {
                return true;
            }
            return false;
        });

    }
}
