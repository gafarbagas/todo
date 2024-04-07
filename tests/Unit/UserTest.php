<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'test',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'username' => 'test',
        ]);
    }

    public function test_user_register_then_login()
    {
        $user = $this->create_user();

        auth()->login($user);

        $this->assertTrue(auth()->check());
    }

    public function test_user_create_redundant_username()
    {
        $this->create_user();

        try {
            $this->create_user();
        } catch (\Exception $e) {
            $this->assertDatabaseCount('users', 1);
        }

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_register_then_logout()
    {
        $user = $this->create_user();

        auth()->login($user);

        auth()->logout();

        $this->assertFalse(auth()->check());
    }

    public function test_user_login()
    {
        $user = $this->create_user();

        auth()->attempt([
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertTrue(auth()->check());
    }

    public function test_user_login_invalid()
    {
        $user = $this->create_user();

        auth()->attempt([
            'username' => $user->username,
            'password' => 'invalid',
        ]);

        $this->assertFalse(auth()->check());

        auth()->attempt([
            'username' => 'random',
            'password' => 'invalid',
        ]);

        $this->assertFalse(auth()->check());
    }

    public function test_user_has_tasks()
    {
        $user = $this->login_user();

        $user->tasks()->create([
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);
    }

    public function test_user_logout()
    {
        $this->login_user();

        auth()->logout();

        $this->assertFalse(auth()->check());
    }

    private function create_user()
    {
        return User::create([
            'name' => 'Test User',
            'username' => 'test',
            'password' => bcrypt('password'),
        ]);
    }

    private function login_user()
    {
        $user = $this->create_user();

        auth()->login($user);

        return $user;
    }
}