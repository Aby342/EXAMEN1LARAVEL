<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            // Admin can see all tasks
            $tasks = Task::with(['project', 'assignedTo', 'comments.author'])->get();
        } else {
            // Users can see tasks from their projects or assigned to them
            $tasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->orWhere('assigned_to', $user->id)
            ->with(['project', 'assignedTo', 'comments.author'])
            ->get();
        }

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'deadline' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $user = $request->user();
        $project = Project::findOrFail($data['project_id']);
        
        // Check if user can create tasks in this project
        if (!$user->isAdmin() && $project->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'deadline' => $data['deadline'] ?? null,
            'project_id' => $data['project_id'],
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        return response()->json($task->load(['project', 'assignedTo']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can access this task
        if (!$user->isAdmin() && 
            $task->project->owner_id !== $user->id && 
            $task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($task->load(['project', 'assignedTo', 'comments.author']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can update this task
        if (!$user->isAdmin() && 
            $task->project->owner_id !== $user->id && 
            $task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'deadline' => 'sometimes|nullable|date',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        $task->update($data);

        return response()->json($task->load(['project', 'assignedTo']));
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can delete this task
        if (!$user->isAdmin() && $task->project->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
