<?php

use Illuminate\Support\Facades\Route;
use A2Workspace\LaravelJwt\LaravelJwt;
use A2Workspace\SocialEntry\SocialEntry;

/*
|--------------------------------------------------------------------------
| User Auth Routes
|--------------------------------------------------------------------------
|
| Here define the routes used for authentication / authorization client user.
|
| Includes:
| - POST: /auth/login
| - POST: /auth/logout
| - POST: /auth/refresh
| - GET: /auth/user
| - POST: /auth/socialite/token
| - POST: /auth/socialite/login
| - POST: /auth/socialite/connect
| - POST: /auth/socialite/disconnect
|
*/

LaravelJwt::routes([
    'prefix' => '/auth',
    'middleware' => 'assign.guard:client',
    'namespace' => '\App\Http\Controllers\Client',
    'as' => 'auth.',
]);

SocialEntry::routes();

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
|
| Here define the routes used for authentication / authorization admin user.
|
| Includes:
| - POST: /admin/auth/login
| - POST: /admin/auth/logout
| - POST: /admin/auth/refresh
| - GET: /admin/auth/user
| - POST: /admin/auth/socialite/token
| - POST: /admin/auth/socialite/login
| - POST: /admin/auth/socialite/connect
| - POST: /admin/auth/socialite/disconnect
|
*/

LaravelJwt::routes([
    'prefix' => '/admin/auth',
    'middleware' => 'assign.guard:admin',
    'namespace' => '\App\Http\Controllers\Admin',
    'as' => 'admin.auth.',
]);

SocialEntry::routes(function ($registrar) {
    $registrar->all([
        'middleware' => 'assign.guard:admin',
        'prefix' => '/admin/auth/socialite',
        'as' => 'admin.social-entry.',
    ]);
});
