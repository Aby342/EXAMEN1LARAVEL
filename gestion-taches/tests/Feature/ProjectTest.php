<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_user_can_create_project()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/projects', [
            'name' => 'Test Project',
            'description' => 'Test Description'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id', 'name', 'description', 'owner_id', 'created_at', 'updated_at'
                ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
            'owner_id' => $this->user->id
        ]);
    }

    public function test_user_can_view_own_projects()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/projects');

        $response->assertStatus(200)
                ->assertJsonCount(1);
    }

    public function test_user_cannot_view_other_user_projects()
    {
        $otherUser = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $otherUser->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/projects');

        $response->assertStatus(200)
                ->assertJsonCount(0);
    }

    public function test_admin_can_view_all_projects()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $adminToken = $admin->createToken('admin-token')->plainTextToken;

        $project1 = Project::factory()->create(['owner_id' => $this->user->id]);
        $project2 = Project::factory()->create(['owner_id' => $admin->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/projects');

        $response->assertStatus(200)
                ->assertJsonCount(2);
    }
} 