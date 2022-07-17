<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Resources\AdminResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use A2Workspace\LaravelJwt\AuthenticatesUsers;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 回傳認證守衛
     *
     * @return \PHPOpenSourceSaver\JWTAuth\JWTGuard
     */
    protected function guard(): JWTGuard
    {
        return Auth::guard('admin');
    }

    /**
     * {@inheritDoc}
     */
    public function me(Request $request)
    {
        $user = $request->user();

        abort_unless(is_a($user, Admin::class), 403);

        return new AdminResource($user);
    }
}
