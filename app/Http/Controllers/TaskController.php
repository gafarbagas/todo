<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get all user's tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $tasks = auth()->user()->tasks()->get();

        return response()->json([
            'tasks' => $tasks,
        ]);
    }

    /**
     * Create a new task
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $task = auth()->user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Update task title
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDescription(Request $request, string $id)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $task = auth()->user()->tasks()->where('id', $id)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->description = $request->description;
        $task->save();

        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Delete task
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(string $id)
    {
        $task = auth()->user()->tasks()->where('id', $id)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted',
        ]);
    }

    /**
     * Update task completed status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompleted(Request $request, string $id)
    {
        $request->validate([
            'completed' => 'required',
        ]);

        $task = auth()->user()->tasks()->where('id', $id)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->completed = $request->completed;
        $task->save();

        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Update task priorities by ordered ids
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePriorities(Request $request)
    {
        $request->validate([
            'tasks' => 'required',
        ]);

        $ids = $request->tasks;

        $tasks = new Task();
        $reorder = $tasks->reorderTasksByIds($ids);

        if (!$reorder) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Priorities updated',
        ]);

    }
}
