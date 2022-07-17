<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use A2Workspace\SocialEntry\SocialEntry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            SocialEntry::routes();
        }
    }
}
