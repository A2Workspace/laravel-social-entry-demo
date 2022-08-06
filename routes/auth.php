<?php

use Illuminate\Support\Facades\Route;
use A2Workspace\LaravelJwt\LaravelJwt;
use A2Workspace\SocialEntry\SocialEntry;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| This file define all authentication / authorization routes for your application.
| These routes are loaded by the RouteServiceProvider.
|
*/

SocialEntry::routes();

LaravelJwt::routes([
    'prefix' => '/auth',
    'middleware' => 'assign.guard:client',
    'namespace' => '\App\Http\Controllers\Client',
    'as' => 'auth.',
]);

SocialEntry::routes(function ($registrar) {
    $registrar->all([
        'middleware' => 'assign.guard:admin',
        'prefix' => '/admin/auth/socialite',
        'as' => 'admin.social-entry.',
    ]);
});

LaravelJwt::routes([
    'prefix' => '/admin/auth',
    'middleware' => 'assign.guard:admin',
    'namespace' => '\App\Http\Controllers\Admin',
    'as' => 'admin.auth.',
]);
