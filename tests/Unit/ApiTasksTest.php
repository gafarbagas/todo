<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class ApiTasksTest extends TestCase
{
    private $headers = [
        'Accept' => 'application/json',
    ];

    // before each test, create a user and login
    protected function setUp(): void
    {
        parent::setUp();

        $this->register_user_and_login();
    }

    public function test_create_task(): void
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task',
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_get_all_tasks(): void
    {
        $response = $this->get('/api/tasks', $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'priority',
                    'completed',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_update_task_description(): void
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task',
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals('Test Task', $response['data']['description']);

        $task = $response['data'];

        $response = $this->put('/api/tasks/' . $task['id'] . '/description', [
            'description' => 'Updated Test Task',
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'completed',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals('Updated Test Task', $response['data']['description']);
    }

    public function test_update_task_completed(): void
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task',
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals('Test Task', $response['data']['description']);

        $task = $response['data'];

        $response = $this->put('/api/tasks/' . $task['id'] . '/completed', [
            'completed' => true,
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'completed',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals(true, $response['data']['completed']);
    }

    public function test_update_task_priorities(): void
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task 1',
            'description' => 'Test Task 1',
        ], $this->headers);

        $response = $this->post('/api/tasks', [
            'title' => 'Test Task 2',
            'description' => 'Test Task 2',
        ], $this->headers);

        $response = $this->post('/api/tasks', [
            'title' => 'Test Task 3',
            'description' => 'Test Task 3',
        ], $this->headers);

        $response = $this->get('/api/tasks', $this->headers);

        $response->assertStatus(200);

        $data = $response['data'];

        // save all data['id'] to $tasks[];
        $tasks = [];
        foreach ($data as $task) {
            $tasks[] = $task['id'];
        }

        // randomize the order of $tasks
        shuffle($tasks);

        $response = $this->put('/api/tasks/sort', [
            'tasks' => $tasks,
        ], $this->headers);
        $response->assertStatus(200);

        $response = $this->get('/api/tasks', $this->headers);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'priority',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        
        $this->assertEquals($tasks[0], $response['data'][0]['id']);
    }

    public function test_delete_task(): void
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task',
        ], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals('Test Task', $response['data']['description']);

        $task = $response['data'];

        $response = $this->delete('/api/tasks/' . $task['id'], [], $this->headers);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
        ]);
    }

    private function register_user_and_login(): void
    {
        $this->register_user();

        $response = $this->post('/api/login', [
            'username' => 'usertest',
            'password' => 'password',
        ]);

        $this->headers = [
            'Authorization' => 'Bearer ' . $response['token'],
            'Accept' => 'application/json',
        ];
    }

    private function register_user(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'username' => 'usertest',
            'password' => 'password',
        ], $this->headers);
    }
}
