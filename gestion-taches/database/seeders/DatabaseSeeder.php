<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        // Create projects
        $project1 = Project::create([
            'name' => 'E-commerce Website',
            'description' => 'Development of a modern e-commerce platform',
            'owner_id' => $user1->id,
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App',
            'description' => 'iOS and Android mobile application',
            'owner_id' => $user2->id,
        ]);

        // Create tasks
        $task1 = Task::create([
            'title' => 'Design Homepage',
            'description' => 'Create responsive homepage design',
            'status' => 'in_progress',
            'deadline' => now()->addDays(7),
            'project_id' => $project1->id,
            'assigned_to' => $user1->id,
        ]);

        $task2 = Task::create([
            'title' => 'Setup Database',
            'description' => 'Configure database schema and migrations',
            'status' => 'completed',
            'deadline' => now()->addDays(3),
            'project_id' => $project1->id,
            'assigned_to' => $user2->id,
        ]);

        $task3 = Task::create([
            'title' => 'API Development',
            'description' => 'Develop RESTful API endpoints',
            'status' => 'pending',
            'deadline' => now()->addDays(14),
            'project_id' => $project2->id,
            'assigned_to' => $user1->id,
        ]);

        // Create comments
        Comment::create([
            'content' => 'Great progress on the design!',
            'author_id' => $user1->id,
            'task_id' => $task1->id,
        ]);

        Comment::create([
            'content' => 'Database setup completed successfully',
            'author_id' => $user2->id,
            'task_id' => $task2->id,
        ]);

        Comment::create([
            'content' => 'Starting API development next week',
            'author_id' => $user1->id,
            'task_id' => $task3->id,
        ]);
    }
}
