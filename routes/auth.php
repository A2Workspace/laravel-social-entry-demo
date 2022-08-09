<?php

use Illuminate\Support\Facades\Route;
use A2Workspace\LaravelJwt\LaravelJwt;
use A2Workspace\SocialEntry\SocialEntry;

/*
|--------------------------------------------------------------------------
| SocialEntry Authorization Routes
|--------------------------------------------------------------------------
|
| Includes:
| - GET: /auth/socialite
| - GET: /auth/socialite/{provider}/callback
|
*/

SocialEntry::routes(function ($registrar) {
    $registrar->forAuthorization();
});

/*
|--------------------------------------------------------------------------
| User Auth Routes
|--------------------------------------------------------------------------
|
| Here define the routes used for authentication / authorization client user.
|
| Includes:
| - POST: /api/auth/login
| - POST: /api/auth/logout
| - POST: /api/auth/refresh
| - GET: /api/auth/user
|
| - POST: /api/auth/socialite/token
| - POST: /api/auth/socialite/login
| - POST: /api/auth/socialite/connect
| - POST: /api/auth/socialite/disconnect
|
*/

LaravelJwt::routes([
    'prefix' => '/api/auth',
    'middleware' => ['api', 'assign.guard:client'],
    'namespace' => '\App\Http\Controllers\Client',
    'as' => 'auth.',
]);

SocialEntry::routes(function ($registrar) {
    $options = [
        'prefix' => '/api/auth/socialite',
        'middleware' => ['api', 'assign.guard:client'],
        'as' => 'auth.social-entry.',
    ];

    $registrar->forAccessToken($options);
    $registrar->forUserAccesses($options);
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
|
| Here define the routes used for authentication / authorization admin user.
|
| Includes:
| - POST: /admin/api/auth/login
| - POST: /admin/api/auth/logout
| - POST: /admin/api/auth/refresh
| - GET: /admin/api/auth/user
|
| - POST: /admin/api/auth/socialite/token
| - POST: /admin/api/auth/socialite/login
| - POST: /admin/api/auth/socialite/connect
| - POST: /admin/api/auth/socialite/disconnect
|
*/

LaravelJwt::routes([
    'prefix' => '/admin/api/auth',
    'middleware' => ['api', 'assign.guard:admin'],
    'namespace' => '\App\Http\Controllers\Admin',
    'as' => 'admin.auth.',
]);

SocialEntry::routes(function ($registrar) {
    $options = [
        'prefix' => '/admin/api/auth/socialite',
        'middleware' => ['api', 'assign.guard:admin'],
        'as' => 'admin.auth.social-entry.',
    ];

    $registrar->forAccessToken($options);
    $registrar->forUserAccesses($options);
});
