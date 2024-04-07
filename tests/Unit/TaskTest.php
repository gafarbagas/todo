<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->login_user();
    }

    public function test_create_task()
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);
    }

    public function test_user_can_create_task()
    {
        $task = $this->create_task();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);
    }

    public function test_user_can_update_task_description()
    {
        $task = $this->create_task();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);

        $task->description = 'Updated Task Description';
        $task->save();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Updated Task Description',
        ]);
    }

    public function test_user_can_delete_task()
    {
        $task = $this->create_task();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);

        $task->delete();

        $this->assertDatabaseMissing('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);
    }

    public function test_reorder_tasks_by_ids_sent()
    {
        $ids = [];
        for($i = 0; $i < 5; $i++) {
            $task = $this->create_task();
            $ids[] = $task->id;
        }

        shuffle($ids);

        $task = new Task();
        $task->reorderTasksByIds($ids);

        $tasks = Task::where('user_id', auth()->user()->id)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($tasks as $key => $task) {
            $this->assertEquals($ids[$key], $task->id);
        }
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

    private function create_task()
    {
        return auth()->user()->tasks()->create([
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]);
    }
}
