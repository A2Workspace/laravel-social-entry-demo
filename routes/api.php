<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use A2Workspace\LaravelJwt\LaravelJwt;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

LaravelJwt::routes([
    'prefix' => '/auth',
    'namespace' => '\App\Http\Controllers\Client',
    'as' => 'auth.',
]);

LaravelJwt::routes([
    'prefix' => '/admin/auth',
    'namespace' => '\App\Http\Controllers\Admin',
    'as' => 'admin.auth.',
]);
