<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are stateless and prefixed with /api.
| Public routes: registration and login.
| Protected routes: user, logout, CRUD for projects, tasks, comments.
|
*/

// Public endpoints (no token required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes (token or SPA cookie)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    function(Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // User routes
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/users', [UserController::class, 'index']);

    // CRUD resources
    Route::apiResource('projects',  ProjectController::class);
    Route::apiResource('tasks',     TaskController::class);
    Route::apiResource('comments',  CommentController::class);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::get('/admin/stats', [AdminController::class, 'projectStats']);
        Route::get('/admin/workload', [AdminController::class, 'userWorkload']);
    });
});
