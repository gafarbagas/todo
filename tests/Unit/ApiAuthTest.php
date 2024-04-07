<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    private $headers = [
        'Accept' => 'application/json',
    ];

    public function test_register_returns_a_successful_response(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'password',
        ], $this->headers);
        
        $response->assertStatus(200);
    }

    public function test_login_returns_a_successful_response(): void
    {
        $response = $this->post('/api/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_login_return_invalid_credentials(): void
    {
        $response = $this->post('/api/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_returns_a_successful_response(): void
    {
        $this->login_user();

        $response = $this->post('/api/logout', [], $this->headers);
        
        $response->assertStatus(200);
    }

    public function test_logout_unauthenticated(): void
    {
        $response = $this->post('/api/logout', [], $this->headers);
        
        $response->assertStatus(401);
    }

    public function test_register_username_exist_failed(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'password',
        ], $this->headers);

        $response->assertStatus(422);
    }

    private function login_user(): void
    {
        $response = $this->post('/api/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $this->headers = [
            'Authorization' => 'Bearer ' . $response['token'],
            'Accept' => 'application/json',
        ];
    }
}
