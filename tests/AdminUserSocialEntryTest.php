<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserSocialEntryTest extends TestCase
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
        $admin = Admin::factory()->create([
            'username' => 'test_admin',
            'password' => bcrypt('test_admin_password'),
        ]);

        $loginResponse = $this->post('/admin/api/auth/login', [
            'username' => 'test_admin',
            'password' => 'test_admin_password',
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
            '/admin/api/auth/socialite/token',
            [
                'code' => $code,
            ],
        );

        $accessResponse->assertJsonStructure([
            'access_token',
        ]);

        // Step 3: Using access_token to connect social account and local user.
        $connectionResposne = $this->postJson(
            '/admin/api/auth/socialite/connect',
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
            'user_id' => $admin->getKey(),
            'user_type' => get_class($admin),
        ]);
    }

    public function test_login_with_social_account()
    {
        $admin = Admin::factory()->create([
            'username' => 'test_admin',
            'password' => bcrypt('test_admin_password'),
        ]);

        $admin->socialIdentifiers()->create([
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
            '/admin/api/auth/socialite/token',
            [
                'code' => $code,
            ],
        );

        $accessResponse->assertJsonStructure([
            'access_token',
        ]);

        // Step 3: Using access_token to login.
        $loginResposne = $this->postJson(
            '/admin/api/auth/socialite/login',
            [
                'access_token' => $accessResponse['access_token']
            ]
        );

        $loginResposne->assertStatus(200);
        $loginResposne->assertJsonStructure([
            'access_token',
        ]);

        // Step 4: Trying to get user data.
        $profileResponse = $this->getJson('/admin/api/auth/user', [
            'Authorization' => "Bearer {$loginResposne['access_token']}"
        ]);

        $profileResponse->assertStatus(200)
            ->assertJsonPath('data.username', 'test_admin');
    }
}
