<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // =========================================================================
    // = Specs
    // =========================================================================

    public function test_admin_user_login_successfully()
    {
        Admin::factory()->create([
            'username' => 'test_admin_user',
            'password' => bcrypt('test_admin_user_password'),
        ]);

        $response = $this->post('/admin/api/auth/login', [
            'username' => 'test_admin_user',
            'password' => 'test_admin_user_password',
        ]);

        $response->assertStatus(200);

        $responsePayload = $response->decodeResponseJson();

        $this->assertArrayHasKey('access_token', $responsePayload);
        $this->assertArrayHasKey('token_type', $responsePayload);
        $this->assertArrayHasKey('expires_in', $responsePayload);

        $this->assertEquals('bearer', $responsePayload['token_type']);

        $response2 = $this->getJson('/admin/api/auth/user', [
            'Authorization' => "Bearer {$responsePayload['access_token']}"
        ]);

        $response2->assertStatus(200)
            ->assertJsonPath('data.username', 'test_admin_user');
    }

    public function test_admin_user_login_failed()
    {
        Admin::factory()->create([
            'username' => 'test_admin_user',
            'password' => bcrypt('test_admin_user_password'),
        ]);

        $response = $this->post('/admin/api/auth/login', [
            'username' => 'test_admin_user',
            'password' => 'WRONG_PASSWORD',
        ]);

        $response->assertStatus(401);
    }
}
