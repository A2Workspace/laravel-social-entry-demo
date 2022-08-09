<?php

namespace Tests;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Config;

trait SetupSocialEntry
{
    protected function setupSocialEntry(): void
    {
        $manager = Socialite::getFacadeRoot();

        $manager->extend('test_auth', function () use ($manager) {
            return $manager->buildProvider(
                \SetupSocialEntry\ProviderStub::class,
                [
                    'client_id' => 'client_id',
                    'client_secret' => 'client_secret',
                    'redirect' => 'redirect',
                ]
            );
        });

        Config::set('social-entry.providers', ['test_auth']);
    }

    protected function touchCallbackAndReturnAuthCode(): string
    {
        $callbackResposne = $this->call(
            'GET',
            '/auth/socialite/test_auth/callback',
            [
                'code' => 'auth_code'
            ]
        );

        $callbackResposne->assertRedirectContains('code=');

        $this->assertDatabaseHas('social_entry_auth_codes', [
            'identifier' => 'social_id',
            'provider' => 'test_auth',
            'revoked' => false,
        ]);

        preg_match('/code=([\w\d]+)/', $callbackResposne->getTargetUrl(), $matches);

        $code = $matches[1] ?? '';

        return $code;
    }
}

namespace SetupSocialEntry;

class ProviderStub extends \Laravel\Socialite\Two\AbstractProvider
{
    protected function getAuthUrl($state)
    {
        return 'http://auth.com/login/oauth/authorize';
    }

    protected function getTokenUrl()
    {
        return 'http://auth.com/login/oauth/access_token';
    }

    protected function hasInvalidState()
    {
        return false;
    }

    public function getAccessTokenResponse($code)
    {
        return [
            'access_token' => 'oauth_access_token',
            'refresh_token' => 'oauth_refresh_token',
            'expires_in' => 'oauth_expires_in',
            'scope' => 'oauth_scope',
        ];
    }

    protected function getUserByToken($token)
    {
        return [];
    }

    protected function mapUserToObject(array $user)
    {
        return new UserStub;
    }
}

class UserStub implements \Laravel\Socialite\Contracts\User
{
    public function getId()
    {
        return 'social_id';
    }

    public function getEmail()
    {
        return 'social_email';
    }

    public function getNickname()
    {
        return 'social_nickname';
    }

    public function getName()
    {
        return 'social_username';
    }

    public function getAvatar()
    {
        return 'social_avatar';
    }

    public function setToken()
    {
        return $this;
    }

    public function setRefreshToken()
    {
        return $this;
    }

    public function setExpiresIn()
    {
        return $this;
    }

    public function setApprovedScopes()
    {
        return $this;
    }
}
