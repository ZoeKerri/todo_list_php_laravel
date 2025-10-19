<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_endpoints()
    {
        // Test register endpoint
        $registerData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'full_name' => 'Test User'
        ];

        $response = $this->postJson('/api/v1/auth/register', $registerData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'email',
                    'fullName'
                ]
            ]);

        // Test login endpoint
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/auth/login', $loginData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'email',
                        'fullName'
                    ]
                ]
            ]);
    }

    public function test_categories_endpoint()
    {
        $response = $this->getJson('/api/v1/category');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'color'
                    ]
                ]
            ]);
    }

    public function test_task_count_endpoints()
    {
        // Create a test user
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'full_name' => 'Test User',
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        // Test total tasks count
        $response = $this->getJson('/api/v1/task/count/day/total?userId=' . $user->id . '&date=2024-12-31');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);

        // Test completed tasks count
        $response = $this->getJson('/api/v1/task/count/day/completed?userId=' . $user->id . '&date=2024-12-31');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }

    public function test_protected_endpoints_require_auth()
    {
        // Test that protected endpoints return 401 without token
        $response = $this->getJson('/api/v1/user/profile');
        $response->assertStatus(401);

        $response = $this->getJson('/api/v1/task');
        $response->assertStatus(401);

        $response = $this->getJson('/api/v1/team/task?teamId=1');
        $response->assertStatus(401);
    }
}
