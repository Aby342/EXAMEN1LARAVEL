<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Get all users with their statistics.
     */
    public function users(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $users = User::withCount(['projects', 'assignedTasks', 'comments'])
            ->with(['projects', 'assignedTasks'])
            ->get();

        return response()->json($users);
    }

    /**
     * Get project statistics.
     */
    public function projectStats(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $stats = [
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'cancelled_tasks' => Task::where('status', 'cancelled')->count(),
            'projects_by_status' => [
                'active' => Project::whereHas('tasks', function ($query) {
                    $query->whereIn('status', ['pending', 'in_progress']);
                })->count(),
                'completed' => Project::whereDoesntHave('tasks', function ($query) {
                    $query->whereIn('status', ['pending', 'in_progress']);
                })->count(),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Get user workload statistics.
     */
    public function userWorkload(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $users = User::withCount(['assignedTasks as total_tasks'])
            ->withCount(['assignedTasks as pending_tasks' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->withCount(['assignedTasks as in_progress_tasks' => function ($query) {
                $query->where('status', 'in_progress');
            }])
            ->withCount(['assignedTasks as completed_tasks' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get();

        return response()->json($users);
    }
}
