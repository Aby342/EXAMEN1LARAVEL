<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            // Admin can see all comments
            $comments = Comment::with(['author', 'task'])->get();
        } else {
            // Users can see comments from their projects or tasks assigned to them
            $comments = Comment::whereHas('task.project', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->orWhereHas('task', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->with(['author', 'task'])
            ->get();
        }

        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $user = $request->user();
        $task = Task::findOrFail($data['task_id']);
        
        // Check if user can comment on this task
        if (!$user->isAdmin() && 
            $task->project->owner_id !== $user->id && 
            $task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment = Comment::create([
            'content' => $data['content'],
            'author_id' => $user->id,
            'task_id' => $data['task_id'],
        ]);

        return response()->json($comment->load(['author', 'task']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can access this comment
        if (!$user->isAdmin() && 
            $comment->task->project->owner_id !== $user->id && 
            $comment->task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($comment->load(['author', 'task']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can update this comment (only author or admin)
        if (!$user->isAdmin() && $comment->author_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($data);

        return response()->json($comment->load(['author', 'task']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();
        
        // Check if user can delete this comment (only author or admin)
        if (!$user->isAdmin() && $comment->author_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
