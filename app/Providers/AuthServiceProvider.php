<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use A2Workspace\LaravelJwt\LaravelJwt;
use A2Workspace\SocialEntry\SocialEntry;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        SocialEntry::routes();

        SocialEntry::routes(function ($registrar) {
            $registrar->all([
                'prefix' => '/admin/auth/socialite',
            ]);
        });

        LaravelJwt::routes([
            'prefix' => '/auth',
            'middleware' => 'assign.guard:client',
            'namespace' => '\App\Http\Controllers\Client',
            'as' => 'auth.',
        ]);

        LaravelJwt::routes([
            'prefix' => '/admin/auth',
            'middleware' => 'assign.guard:admin',
            'namespace' => '\App\Http\Controllers\Admin',
            'as' => 'admin.auth.',
        ]);
    }
}
