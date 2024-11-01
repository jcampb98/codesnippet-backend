<?php

namespace App\Providers;

use App\Models\Code;
use App\Policies\CodePolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Code::class => CodePolicy::class, // Register the code model with CodePolicy
        User::class => UserPolicy::class, // Register the code model with UserPolicy
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}