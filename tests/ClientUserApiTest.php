<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientUserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // =========================================================================
    // = Specs
    // =========================================================================

    public function test_client_user_login_successfully()
    {
        User::factory()->create([
            'username' => 'test_user',
            'password' => bcrypt('test_user_password'),
        ]);

        $response = $this->post('/api/auth/login', [
            'username' => 'test_user',
            'password' => 'test_user_password',
        ]);

        $response->assertStatus(200);

        $responsePayload = $response->decodeResponseJson();

        $this->assertArrayHasKey('access_token', $responsePayload);
        $this->assertArrayHasKey('token_type', $responsePayload);
        $this->assertArrayHasKey('expires_in', $responsePayload);

        $this->assertEquals('bearer', $responsePayload['token_type']);

        $response2 = $this->getJson('/api/auth/user', [
            'Authorization' => "Bearer {$responsePayload['access_token']}"
        ]);

        $response2->assertStatus(200)
            ->assertJsonPath('data.username', 'test_user');
    }

    public function test_client_user_login_failed()
    {
        User::factory()->create([
            'username' => 'test_user',
            'password' => bcrypt('test_user_password'),
        ]);

        $response = $this->post('/api/auth/login', [
            'username' => 'test_user',
            'password' => 'WRONG_PASSWORD',
        ]);

        $response->assertStatus(401);
    }

    public function test_client_user_registr()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'test_user',
            'nickname' => 'test_user',
            'password' => 'test_user_password',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.username', 'test_user');

        $this->assertDatabaseHas('users', [
            'username' => 'test_user',
            'display_name' => 'test_user',
        ]);
    }
}
