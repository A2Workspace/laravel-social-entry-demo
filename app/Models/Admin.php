<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use A2Workspace\LaravelJwt\HasApiTokens;
use A2Workspace\LaravelJwt\Contracts\JWTSubject;
use A2Workspace\SocialEntry\HasSocialIdentifiers;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, HasSocialIdentifiers;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'display_name',
        'is_enabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /* =========================================================================
     * = Scopes
     * =========================================================================
     */

    /**
     * Query only enabled.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeEnabled($query)
    {
        $query->where('is_enabled', true);
    }
}
