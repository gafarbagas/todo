<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getAll()
    {
        $tasks = auth()->user()->tasks()->orderBy('priority', 'asc')->get();

        return response()->json([
            'data' => $tasks,
        ]);
    }

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

        if (!$task) {
            return response()->json([
                'message' => 'Task not created',
            ], 400);
        }

        return response()->json([
            'message' => 'Task created',
            'data' => $task,
        ]);
    }

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

        $task->update([
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Task updated',
            'data' => $task,
        ]);
    }

    public function updateCompleted(Request $request, string $id)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $task = auth()->user()->tasks()->where('id', $id)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update([
            'completed' => $request->completed,
        ]);

        return response()->json([
            'message' => 'Task updated',
            'data' => $task,
        ]);
    }

    public function updatePriorities(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
        ]);

        $tasks = new Task();
        $updated = $tasks->reorderTasksByIds($request->tasks);

        if (!$updated) {
            return response()->json([
                'message' => 'Tasks not updated',
            ], 400);
        }

        return response()->json([
            'message' => 'Tasks updated',
        ]);
    }

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
}
