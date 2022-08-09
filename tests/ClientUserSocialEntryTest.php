<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientUserSocialEntryTest extends TestCase
{
    use RefreshDatabase, SetupSocialEntry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupSocialEntry();
    }

    // =========================================================================
    // = Specs
    // =========================================================================

    public function test_social_account_connection()
    {
        $user = User::factory()->create([
            'username' => 'test_user',
            'password' => bcrypt('test_user_password'),
        ]);

        $loginResponse = $this->post('/api/auth/login', [
            'username' => 'test_user',
            'password' => 'test_user_password',
        ]);

        // Step 1: Third-party service authorization via SocialEntry
        $this->call(
            'GET',
            '/auth/socialite',
            [
                'provider' => 'test_auth',
                'continue' => 'http://example.com/login'
            ]
        );

        // Step 1a: Complete third-party service authorization and return auth_code
        $code = $this->touchCallbackAndReturnAuthCode();

        // Step 2: Using auth_code to complete SocialEntry authorization.
        $accessResponse = $this->postJson(
            '/api/auth/socialite/token',
            [
                'code' => $code,
            ],
        );

        $accessResponse->assertJsonStructure([
            'access_token',
        ]);

        // Step 3: Using access_token to connect social account and local user.
        $connectionResposne = $this->postJson(
            '/api/auth/socialite/connect',
            [
                'access_token' => $accessResponse['access_token']
            ],
            [
                'Authorization' => "Bearer {$loginResponse['access_token']}"
            ]
        );

        $connectionResposne->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', '已成功綁定');

        $this->assertDatabaseHas('social_entry_identifiers', [
            'identifier' => 'social_id',
            'type' => 'test_auth',
            'user_id' => $user->getKey(),
            'user_type' => get_class($user),
        ]);
    }

    public function test_login_with_social_account()
    {
        $user = User::factory()->create([
            'username' => 'test_user',
            'password' => bcrypt('test_user_password'),
        ]);

        $user->socialIdentifiers()->create([
            'identifier' => 'social_id',
            'type' => 'test_auth',
        ]);

        // Step 1: Third-party service authorization via SocialEntry
        $this->call(
            'GET',
            '/auth/socialite',
            [
                'provider' => 'test_auth',
                'continue' => 'http://example.com/login'
            ]
        );

        // Step 1a: Complete third-party service authorization and return auth_code
        $code = $this->touchCallbackAndReturnAuthCode();

        // Step 2: Using auth_code to complete SocialEntry authorization.
        $accessResponse = $this->postJson(
            '/api/auth/socialite/token',
            [
                'code' => $code,
            ],
        );

        $accessResponse->assertJsonStructure([
            'access_token',
        ]);

        // Step 3: Using access_token to login.
        $loginResposne = $this->postJson(
            '/api/auth/socialite/login',
            [
                'access_token' => $accessResponse['access_token']
            ]
        );

        $loginResposne->assertStatus(200);
        $loginResposne->assertJsonStructure([
            'access_token',
        ]);

        // Step 4: Trying to get user data.
        $profileResponse = $this->getJson('/api/auth/user', [
            'Authorization' => "Bearer {$loginResposne['access_token']}"
        ]);

        $profileResponse->assertStatus(200)
            ->assertJsonPath('data.username', 'test_user');
    }

    public function test_register_with_social_account()
    {
        // Step 1: Third-party service authorization via SocialEntry
        $this->call(
            'GET',
            '/auth/socialite',
            [
                'provider' => 'test_auth',
                'continue' => 'http://example.com/login'
            ]
        );

        // Step 1a: Complete third-party service authorization and return auth_code
        $code = $this->touchCallbackAndReturnAuthCode();

        // Step 2: Using auth_code to complete SocialEntry authorization.
        $accessResponse = $this->postJson(
            '/api/auth/socialite/token',
            [
                'code' => $code,
            ],
        );

        $accessResponse->assertJsonStructure([
            'access_token',
        ]);

        // Step 3: Trying to register user with access_token.

        $registrationResponse = $this->postJson('/api/register', [
            'username' => 'test_user',
            'nickname' => 'test_user',
            'password' => 'test_user_password',
            'access_token' => $accessResponse['access_token']
        ]);

        $registrationResponse->assertStatus(201)
            ->assertJsonPath('data.username', 'test_user');

        $this->assertDatabaseHas('users', [
            'username' => 'test_user',
            'display_name' => 'test_user',
        ]);

        $this->assertDatabaseHas('social_entry_identifiers', [
            'identifier' => 'social_id',
            'type' => 'test_auth',
        ]);
    }
}
