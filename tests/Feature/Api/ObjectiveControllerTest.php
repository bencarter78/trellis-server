<?php

namespace Tests\Feature\Api;

use App\Objective;
use App\Project;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class ObjectiveControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_objectives_for_a_project()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $project = factory(Project::class)->create();
        $objectives = factory(Objective::class, 2)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/objectives")
             ->assertStatus(200)
             ->assertJson(['data' => ['objectives' => $objectives->toArray()]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_a_member_of_the_project()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_stores_a_new_objective_for_a_project()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $objective = factory(Objective::class)->make(['project_id' => $project->id]);

        $this->post("/api/projects/{$objective->project->uid}/objectives", [
            'project_id' => $objective->project_uid,
            'name' => $objective->name,
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['objective' => ['name' => $objective->name]]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $objective = factory(Objective::class)->make();

        $this->post("/api/projects/{$objective->project->uid}/objectives", [
            'project_id' => $objective->project_uid,
            'name' => $objective->name,
        ])
             ->assertStatus(403);
    }
}
