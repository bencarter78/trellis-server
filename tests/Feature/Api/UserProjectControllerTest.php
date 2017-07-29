<?php

namespace Tests\Feature\Api;

use App\User;
use App\Project;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserProjectControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_projects_for_the_authenticated_user()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $response = $this->get("/api/projects");

        $response->assertStatus(200)
                 ->assertJson(['data' => ['projects' => [$project->toArray()]]]);
    }
}
